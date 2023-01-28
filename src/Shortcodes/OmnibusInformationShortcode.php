<?php

namespace PerfectWPWCO\Shortcodes;

use PerfectWPWCO\Contracts\Shortcode;
use PerfectWPWCO\Extensions\ProductPageAdditionalInfo;
use PerfectWPWCO\Utils\Arr;

class OmnibusInformationShortcode implements Shortcode
{
    /**
     * Return tag for shortcode
     *
     * @return string
     */
    public function getName(): string
    {
        return 'omnibus_information';
    }

    /**
     * Render shortcode content
     *
     * @param $attributes
     * @return string
     */
    public function render($attributes): string
    {
        $productId = $this->getProductId(Arr::get($attributes, 'product_id'));

        $product = wc_get_product($productId);

        if (!$product) {
            return '';
        }

        return ProductPageAdditionalInfo::getOmnibusInformation($product);
    }

    private function getProductId($productId)
    {
        $productId = intval($productId);

        return !empty($productId) ? $productId : get_the_ID();
    }
}