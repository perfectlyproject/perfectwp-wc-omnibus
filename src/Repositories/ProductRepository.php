<?php

namespace PerfectWPWCO\Repositories;

if (!defined('ABSPATH')) exit;

class ProductRepository
{
    private $historyProductRepository;

    public function __construct()
    {
        $this->historyProductRepository = new HistoryPriceRepository();
    }

    public function findOmnibusPriceByProductId($productId)
    {
        $customOmnibusPrice = !empty(get_post_meta($productId, '_custom_omnibus_enabled', true));

        if ($customOmnibusPrice === true) {
            return [
                'price' => floatval(get_post_meta($productId, '_custom_omnibus_price', true)),
                'date' => get_post_meta($productId, '_custom_omnibus_date', true)
            ];
        } else {
            $historyProduct = $this->historyProductRepository = $this->historyProductRepository->findLowestHistoryPriceIn30Days($productId);

            if (!$historyProduct) {
                return null;
            }

            return [
                'price' => $historyProduct->getPrice(),
                'date'=> $historyProduct->getDate()->format('Y-m-d')
            ];
        }
    }
}