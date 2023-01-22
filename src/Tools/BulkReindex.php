<?php

namespace PerfectWPWCO\Tools;

use PerfectWPWCO\Extensions\ReindexHistoryPriceCron;
use PerfectWPWCO\Repositories\HistoryPriceRepository;
use PerfectWPWCO\Utils\Arr;

class BulkReindex
{
    private $limit = 500;

    private $historyPriceRepository;

    public function __construct()
    {
        $this->historyPriceRepository = new HistoryPriceRepository();
    }

    public function reindex(int $cursorId)
    {
        global $wpdb;

        // Fast retrieve product ids
        $ids = array_map('intval', wp_list_pluck($wpdb->get_results($wpdb->prepare('SELECT `ID` FROM ' . $wpdb->posts . '
                WHERE post_status = %s AND post_type = %s AND ID > %d LIMIT %d
            ', 'publish', 'product', $cursorId, $this->limit), ARRAY_A), 'ID'));

        $cursorId = end($ids);
        if (empty($ids)) {
            return;
        }

        $products = \wc_get_products([
            'include' => $ids,
            'limit' => $this->limit
        ]);

        foreach ($products as $product) {
            if ($product instanceof \WC_Product_Variable) {
                foreach (Arr::get($product->get_variation_prices(), 'price') as $id => $price) {
                    $this->historyPriceRepository->pushPrice($id, $price);
                }

                continue;
            }

            $this->historyPriceRepository->pushPrice($product->get_id(), $product->get_price());
        }

        ReindexHistoryPriceCron::dispatch($cursorId);
    }
}