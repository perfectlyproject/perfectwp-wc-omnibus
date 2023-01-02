<?php

namespace PerfectWPWCO\Extensions;

use PerfectWPWCO\Plugin;
use PerfectWPWCO\Utils\Arr;

class Shortcode
{
    public function boot()
    {
        add_shortcode(Plugin::SLUG . '_omnibus_information', [$this, 'renderShortcode']);
    }

    public function renderShortcode($attributes)
    {
        $productId = Arr::get($attributes, 'product_id');

        if (empty($productId)) {
            $productId = get_the_ID();
        }

        $product = wc_get_product($productId);

        if (!$product) {
            return '';
        }

        return ProductPageAdditionalInfo::getOmnibusInformation($product);
    }
}