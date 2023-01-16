<?php

namespace PerfectWPWCO\Tools;

use PerfectWPWCO\Repositories\HistoryPriceRepository;
use PerfectWPWCO\Utils\Arr;

class BulkReindex
{
    public function reindex()
    {
        global $wpdb;

        $cursorId = 0;
        $limit = 100;

        while(true) {
            $ids = array_map('intval', wp_list_pluck($wpdb->get_results($wpdb->prepare('SELECT `ID` FROM ' . $wpdb->posts . '
                WHERE post_status = %s AND post_type = %s AND ID > %d LIMIT %d
            ', 'publish', 'product', $cursorId, $limit), ARRAY_A), 'ID'));

            $cursorId = end($ids);
            if (empty($ids)) {
                break;
            }

            $products = \wc_get_products([
                'include' => $ids,
                'limit' => $limit
            ]);

            foreach ($products as $product) {
                $repository = new HistoryPriceRepository();
                $repository->pushPrice($product->get_id(), $product->get_price());
            }
        }
    }
}