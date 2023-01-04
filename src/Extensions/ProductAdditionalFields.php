<?php

namespace PerfectWPWCO\Extensions;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Plugin;
use PerfectWPWCO\Repositories\HistoryPriceRepository;
use PerfectWPWCO\Utils\Template;

class ProductAdditionalFields
{
    private $repositoryHistoryPrice;

    public function __construct()
    {
        $this->repositoryHistoryPrice = new HistoryPriceRepository();
    }

    public function boot()
    {
        add_action('woocommerce_product_options_pricing', [$this, 'simpleProductAdditionalFields']);
        add_action('woocommerce_variation_options_pricing', [$this, 'variationProductAdditionalFields'], 10, 3);
    }

    public function simpleProductAdditionalFields()
    {
        $productId = intval($_GET['post']);
        $historyPrice = $this->repositoryHistoryPrice->findLowestHistoryPriceInDays($productId);

        (new Template())
            ->setPath(Plugin::getInstance()->basePath('templates/admin/omnibus-price-simple.php'))
            ->setParams([
            'historyPrice' => $historyPrice
        ])->display();
    }

    public function variationProductAdditionalFields($loop, $variationData, $variation)
    {
        $productId = $variation->ID;
        $historyPrice = $this->repositoryHistoryPrice->findLowestHistoryPriceInDays($productId);

        (new Template())
            ->setPath(Plugin::getInstance()->basePath('templates/admin/omnibus-price-simple.php'))
            ->setParams([
                'historyPrice' => $historyPrice
            ])->display();
    }
}