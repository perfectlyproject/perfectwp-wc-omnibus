<?php

namespace PerfectWPWCO\Extensions;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\LowestPriceCalculator;
use PerfectWPWCO\Plugin;
use PerfectWPWCO\Utils\Template;

class ProductAdditionalFields
{
    private $lowestPriceCalculator;

    public function __construct()
    {
        $this->lowestPriceCalculator = new LowestPriceCalculator();
    }

    public function boot()
    {
        add_action('woocommerce_product_options_pricing', [$this, 'simpleProductAdditionalFields']);
        add_action('woocommerce_variation_options_pricing', [$this, 'variationProductAdditionalFields'], 10, 3);
    }

    public function simpleProductAdditionalFields()
    {
        $productId = intval($_GET['post']);

        (new Template())
            ->setPath(Plugin::getInstance()->basePath('templates/admin/omnibus-price-simple.php'))
            ->setParams([
                'historyPrice' => $this->lowestPriceCalculator->getLowestPrice(wc_get_product($productId)),
                'hasHistoryPrice' => $this->lowestPriceCalculator->hasHistoryPrice($productId)
        ])->display();
    }

    public function variationProductAdditionalFields($loop, $variationData, $variation)
    {
        $productId = $variation->ID;

        (new Template())
            ->setPath(Plugin::getInstance()->basePath('templates/admin/omnibus-price-simple.php'))
            ->setParams([
                'historyPrice' => $this->lowestPriceCalculator->getLowestPrice(wc_get_product($productId)),
                'hasHistoryPrice' => $this->lowestPriceCalculator->hasHistoryPrice($productId)
            ])->display();
    }
}