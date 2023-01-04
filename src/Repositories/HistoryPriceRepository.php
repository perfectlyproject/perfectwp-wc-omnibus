<?php

namespace PerfectWPWCO\Repositories;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Models\HistoryPrice;
use PerfectWPWCO\Models\Options;

class HistoryPriceRepository
{
    /**
     * @param int $productId
     * @return HistoryPrice|null
     * @throws \Exception
     */
    public function findLowestHistoryPriceInDaysFromOptions(int $productId)
    {
        return $this->findLowestHistoryPriceInDays($productId, Options::getLowestPriceNumberOfDays());
    }

    /**
     * @param int $productId
     * @param int $fromDays
     * @return HistoryPrice|null
     * @throws \Exception
     */
    public function findLowestHistoryPriceInDays(int $productId, int $fromDays = 30)
    {
        if ($fromDays <= 0) {
            throw new \InvalidArgumentException('From days has to be greater than 0');
        }

        $date = new \DateTime('-' . $fromDays . 'days');

        global $wpdb;

        $params = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . HistoryPrice::getPrefixedTable() . ' WHERE `product_id` = %d && `end_date` >= %s ORDER BY price ASC LIMIT 1', $productId, $date->format('Y-m-d')), ARRAY_A);

        if ($params === null) {
            return null;
        }

        return HistoryPrice::buildModel($params);
    }

    /**
     * @param int $productId
     * @return HistoryPrice|null
     */
    public function findLastHistoryPrice(int $productId)
    {
        global $wpdb;

        $params = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . HistoryPrice::getPrefixedTable() . ' WHERE product_id = %d ORDER BY id DESC LIMIT 1', $productId), ARRAY_A);

        if ($params === null) {
            return null;
        }

        return HistoryPrice::buildModel($params);
    }
}