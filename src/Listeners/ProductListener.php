<?php

namespace PerfectWPWCO\Listeners;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Models\HistoryPrice;
use PerfectWPWCO\Repositories\HistoryPriceRepository;
use PerfectWPWCO\Utils\Arr;

class ProductListener
{
    private $historyProductRepository;

    public function __construct()
    {
        $this->historyProductRepository = new HistoryPriceRepository();
    }

    public function hooks()
    {
        add_action( 'woocommerce_after_product_object_save', [$this, 'onSaveProduct'], 10, 1 );
    }

    public function onSaveProduct(\WC_Product $product)
    {
        if (empty($product->get_price())) {
            return;
        }

        $this->maybeAddProductHistory($product->get_id(), $product->get_price());

        if ($product instanceof \WC_Product_Variable) {
            foreach (Arr::get($product->get_variation_prices(), 'price') as $id => $price) {
                $this->maybeAddProductHistory($id, $price);
            }
        }
    }

    public function maybeAddProductHistory($productId, $productPrice)
    {
        $newPrice = floatval($productPrice);
        $lastHistoryPrice = $this->historyProductRepository->findLastHistoryPrice($productId);
        $lastPrice = $lastHistoryPrice !== null ? $lastHistoryPrice->getPrice() : null;

        if ($newPrice === $lastPrice) {
            return;
        }

        $historyPrice = new HistoryPrice();
        $historyPrice->setProductId($productId);
        $historyPrice->setPrice($newPrice);
        $historyPrice->setDate(new \DateTime('now'));

        $historyPrice->save();
    }
}