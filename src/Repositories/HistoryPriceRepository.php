<?php

namespace PerfectWPWCO\Repositories;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Models\HistoryPrice;

class HistoryPriceRepository
{
    public function findLowestHistoryPriceInDays($productId, $fromDays = 30)
    {
        $date = new \DateTime('-' . intval($fromDays) . 'days');

        global $wpdb;

        $params = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . HistoryPrice::getPrefixedTable() . ' WHERE `product_id` = %d && `end_date` >= %s ORDER BY price ASC LIMIT 1', $productId, $date->format('Y-m-d')), ARRAY_A);

        if ($params === null) {
            return null;
        }

        return HistoryPrice::buildModel($params);
    }

    public function findLastHistoryPrice($productId)
    {
        global $wpdb;

        $params = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . HistoryPrice::getPrefixedTable() . ' WHERE product_id = %d ORDER BY id DESC LIMIT 1', $productId), ARRAY_A);

        if ($params === null) {
            return null;
        }

        return HistoryPrice::buildModel($params);
    }
}