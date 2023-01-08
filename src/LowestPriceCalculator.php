<?php

namespace PerfectWPWCO;

use PerfectWPWCO\Models\HistoryPrice;
use PerfectWPWCO\Models\Options;
use PerfectWPWCO\Repositories\HistoryPriceRepository;

class LowestPriceCalculator
{
    public function getLowestPrice($product)
    {
        if (!$product instanceof \WC_Product) {
            return null;
        }

        $historyPriceRepository = new HistoryPriceRepository();
        $historyPrice = $historyPriceRepository->findLowestHistoryPriceInDaysFromOptions($product->get_id());

        // Show current if not found in history price repository
        if (empty($historyPrice)) {
            $historyPrice = new HistoryPrice();
            $historyPrice->setProductId($product->get_id());
            $historyPrice->setStartDate(\DateTimeImmutable::createFromMutable($product->get_date_modified()));

            if (Options::isCalculateIncludeCurrentPrice() === false) {
                $historyPrice->setPrice($product->get_regular_price());
            } else {
                $historyPrice->setPrice($product->get_price());
            }
        }

        return $historyPrice;
    }

    public function hasHistoryPrice(int $productId): bool
    {
        $historyPriceRepository = new HistoryPriceRepository();

        return $historyPriceRepository->findLowestHistoryPriceInDaysFromOptions($productId) !== null;
    }
}