<?php

namespace PerfectWPWCO\ViewModel;

use PerfectWPWCO\Models\HistoryPrice;
use PerfectWPWCO\Models\Options;

class FrontHistoryPriceViewModel
{
    private $product;

    /** @var HistoryPrice */
    private $historyPrice;

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

    public function getMessage(): string
    {
        $message = Options::getMessageLowestPrice();

        $attributes = [
            '[days]' => Options::getLowestPriceNumberOfDays(),
            '[price]' => wc_price($this->getPrice())
        ];

        foreach ($attributes as $attribute => $value) {
            $message = str_replace($attribute, $value, $message);
        }

        return $message;
    }
}