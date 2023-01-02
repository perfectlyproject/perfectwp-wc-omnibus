<?php

namespace PerfectWPWCO\ViewModel;

use PerfectWPWCO\Models\HistoryPrice;

class HistoryPriceViewModel
{
    private $product;

    private HistoryPrice $historyPrice;

    public function __construct($product, HistoryPrice $historyPrice)
    {
        $this->product = $product;
        $this->historyPrice = $historyPrice;
    }

    /**
     * @return float|string
     */
    public function getPrice()
    {
        $taxDisplayMode = get_option('woocommerce_tax_display_shop');
        $historyPrice = $this->historyPrice->getPrice();

        if (!$historyPrice) {
            return '';
        }

        $args = [
            'price' => $historyPrice
        ];

        return 'incl' === $taxDisplayMode ? wc_get_price_including_tax($this->product, $args) : wc_get_price_excluding_tax($this->product, $args);
    }
}