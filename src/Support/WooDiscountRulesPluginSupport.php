<?php

namespace PerfectWPWCO\Support;

use PerfectWPWCO\Extensions\ReindexHistoryPriceCron;
use PerfectWPWCO\Plugin;
use PerfectWPWCO\Utils\Arr;
use Wdr\App\Controllers\ManageDiscount;

class WooDiscountRulesPluginSupport
{
    public function boot()
    {
        add_action('plugins_loaded', function() {
            if (!defined('WDR_VERSION')) {
                return;
            }

            add_filter(Plugin::SLUG . '_before_next_history_price_pushed', [$this, 'filterBeforeHistoryPricePushed'], 10, 3);
            add_filter(Plugin::SLUG . '_product_is_on_sale', [$this, 'filterProductIsOnSale'], 10, 2);

            add_action('advanced_woo_discount_rules_after_save_rule', [$this, 'hookAdvancedWooDiscountRulesAfterSaveRule']);
        });
    }

    public function filterProductIsOnSale($isOnSale, $productId)
    {
        $discount = ManageDiscount::calculateInitialAndDiscountedPrice($productId, 1);

        if ($isOnSale === true) {
            return true;
        }

        return $discount !== false;
    }

    public function hookAdvancedWooDiscountRulesAfterSaveRule()
    {
        if (!wp_next_scheduled(ReindexHistoryPriceCron::getActionName())) {
            wp_schedule_single_event(time(), ReindexHistoryPriceCron::getActionName());
        }
    }

    public function filterBeforeHistoryPricePushed($newPrice, $lastPrice, $productId)
    {
        $discount = ManageDiscount::calculateInitialAndDiscountedPrice($productId, 1);

        $discountedPrice = Arr::get($discount, 'discounted_price');

        if (!empty($discountedPrice)) {
            $newPrice = $discountedPrice;
        }

        return $newPrice;
    }
}