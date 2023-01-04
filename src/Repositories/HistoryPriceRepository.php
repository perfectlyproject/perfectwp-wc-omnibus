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
        return $this->findLowestHistoryPriceInDays($productId, Options::getLowestPriceNumberOfDays(), Options::isCalculateWithCurrentPrice());
    }

    /**
     * @param int $productId
     * @param int $fromDays
     * @return HistoryPrice|null
     * @throws \Exception
     */
    public function findLowestHistoryPriceInDays(int $productId, int $fromDays = 30, bool $withCurrentPrice = false)
    {
        if ($fromDays <= 0) {
            throw new \InvalidArgumentException('From days has to be greater than 0');
        }

        $date = new \DateTime('-' . $fromDays . 'days');

        global $wpdb;

        if ($withCurrentPrice === false) {
            $result = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . HistoryPrice::getPrefixedTable() . ' WHERE `product_id` = %d AND `end_date` >= %s ORDER BY price ASC LIMIT 1', $productId, $date->format('Y-m-d')), ARRAY_A);
        } else {
            $result = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . HistoryPrice::getPrefixedTable() . ' WHERE `product_id` = %d AND (`end_date` >= %s OR `end_date` IS NULL) ORDER BY price ASC LIMIT 1', $productId, $date->format('Y-m-d')), ARRAY_A);
        }

        return $result ? HistoryPrice::buildModel($result) : null;
    }

    /**
     * @param int $productId
     * @return HistoryPrice|null
     */
    public function findLastHistoryPrice(int $productId)
    {
        global $wpdb;

        $result = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . HistoryPrice::getPrefixedTable() . ' WHERE product_id = %d ORDER BY id DESC LIMIT 1', $productId), ARRAY_A);

        return $result ? HistoryPrice::buildModel($result) : null;
    }
}