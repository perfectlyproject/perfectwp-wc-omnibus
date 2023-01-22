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
            add_action('advanced_woo_discount_rules_after_delete_rule', [$this, 'hookAdvancedWooDiscountRulesAfterDeleteRule']);
            add_action('wp_ajax_wdr_ajax', [$this, 'hookWpAjaxWdrAjax'], 1, 0);
        });
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

    public function filterProductIsOnSale($isOnSale, $productId)
    {
        $discount = ManageDiscount::calculateInitialAndDiscountedPrice($productId, 1);

        if ($isOnSale === true) {
            return true;
        }

        return $discount !== false;
    }

    /**
     * Catch change status of rule
     * Refactor it in future when action will be added to WooDiscountRules Plugin
     *
     * @return void
     */
    public function hookWpAjaxWdrAjax()
    {
        if ($_REQUEST['method'] === 'manage_status') {
            ReindexHistoryPriceCron::dispatch(0, 10);
        }
    }

    public function hookAdvancedWooDiscountRulesAfterSaveRule()
    {
        ReindexHistoryPriceCron::dispatch(0, 1);
    }

    public function hookAdvancedWooDiscountRulesAfterDeleteRule()
    {
        ReindexHistoryPriceCron::dispatch(0, 1);
    }
}