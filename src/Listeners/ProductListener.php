<?php

namespace PerfectWPWCO\Listeners;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Repositories\HistoryPriceRepository;
use PerfectWPWCO\Utils\Arr;

class ProductListener
{
    private $historyProductRepository;

    public function __construct()
    {
        $this->historyProductRepository = new HistoryPriceRepository();
    }

    public function register()
    {
        add_action('woocommerce_after_product_object_save', [$this, 'onSaveProduct'], 10, 1);
    }

    public function unregister()
    {
        remove_action('woocommerce_after_product_object_save', [$this, 'onSaveProduct'], 10);
    }

    public function onSaveProduct(\WC_Product $product)
    {
        if (empty($product->get_price()) || $product->get_status() === 'draft') {
            return;
        }

        if ($product instanceof \WC_Product_Variable) {
            foreach (Arr::get($product->get_variation_prices(), 'price') as $id => $price) {
                $this->historyProductRepository->pushPrice($id, $price);
            }

            return;
        }

        $this->historyProductRepository->pushPrice($product->get_id(), $product->get_price());
    }
}