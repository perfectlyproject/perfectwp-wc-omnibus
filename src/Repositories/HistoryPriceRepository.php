<?php

namespace PerfectWPWCO\Repositories;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Models\HistoryPrice;
use PerfectWPWCO\Models\Options;

class HistoryPriceRepository
{
    const FROM_NOW_DATE = 'now_date';
    const FROM_LAST_CHANGED_PRICE_DATE = 'last_changed_price_date';

    /**
     * @param int $productId
     * @return HistoryPrice|null
     * @throws \Exception
     */
    public function findLowestHistoryPriceInDaysFromOptions(int $productId)
    {
        return $this->findLowestHistoryPriceInDays($productId, Options::getLowestPriceNumberOfDays(), Options::isCalculateIncludeCurrentPrice(), Options::getCalculateLowestPriceFrom());
    }

    /**
     * @param int $productId
     * @param int $fromDays
     * @param bool $includeCurrentPrice
     * @param string $from
     * @return HistoryPrice|null
     * @throws \Exception
     */
    public function findLowestHistoryPriceInDays(int $productId, int $fromDays = 30, bool $includeCurrentPrice = false, string $from = self::FROM_LAST_CHANGED_PRICE_DATE)
    {
        global $wpdb;

        if ($fromDays <= 0) {
            throw new \InvalidArgumentException('From days has to be greater than 0');
        }

        if (!in_array($from, [self::FROM_NOW_DATE, self::FROM_LAST_CHANGED_PRICE_DATE])) {
            throw new \InvalidArgumentException('Invalid from value');
        }

        if ($from === self::FROM_NOW_DATE) {
            $dateFrom = new \DateTime('-' . $fromDays . 'days');
        } elseif ($from === self::FROM_LAST_CHANGED_PRICE_DATE) {
            $lastChanged = $this->findLastHistoryPrice($productId);

            if ($lastChanged) {
                $dateFrom = $lastChanged->getStartDate()->modify('-' . $fromDays . 'days');
            } else {
                return null;
            }
        }

        if ($includeCurrentPrice === false) {
            $result = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . HistoryPrice::getPrefixedTable() . ' WHERE `product_id` = %d AND `end_date` >= %s ORDER BY price ASC LIMIT 1', $productId, $dateFrom->format('Y-m-d')), ARRAY_A);
        } else {
            $result = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . HistoryPrice::getPrefixedTable() . ' WHERE `product_id` = %d AND (`end_date` >= %s OR `end_date` IS NULL) ORDER BY price ASC LIMIT 1', $productId, $dateFrom->format('Y-m-d')), ARRAY_A);
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

    /**
     * @param int $productId
     * @return array
     */
    public function findAllByProductId(int $productId, $orderBy = 'start_date', $order = 'DESC'): array
    {
        global $wpdb;

        $results = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . HistoryPrice::getPrefixedTable() . ' WHERE `product_id` = %d ORDER BY %s %s', $productId, $orderBy, $order), ARRAY_A);

        return array_map(function($result) {
                return HistoryPrice::buildModel($result);
            }, $results);
    }
}