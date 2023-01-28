<?php

namespace PerfectWPWCO\Extensions;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\LowestPriceCalculator;
use PerfectWPWCO\Models\Options;
use PerfectWPWCO\Plugin;
use PerfectWPWCO\Repositories\HistoryPriceRepository;
use PerfectWPWCO\Utils\Arr;
use PerfectWPWCO\Utils\Template;
use PerfectWPWCO\ViewModel\FrontHistoryPriceViewModel;

class ProductPageAdditionalInfo
{
    public function boot()
    {
        add_action('woocommerce_get_price_html', [$this, 'filterGetPriceHtml'], 200, 2);
    }

    public function filterGetPriceHtml($price, $product)
    {
        if (is_admin() || $product instanceof \WC_Product_Variable ||
            (!apply_filters(Plugin::SLUG . '_product_is_on_sale', $product->is_on_sale(), $product->get_id()) && Options::isShowOnlyForSale())) {
            return $price;
        }

        if (is_single()
            && (get_queried_object_id() === $product->get_id() || get_queried_object_id() === $product->get_parent_id())
            && Options::isShowOnProductPage()) {
            return $this->renderPrice($price, $this->getOmnibusInformation($product));
        }

        if (is_archive() && !is_tax() && Options::isShowOnArchivePage()) {
            return $this->renderPrice($price, $this->getOmnibusInformation($product));
        }

        if (is_tax() && Options::isShowOnTaxonomyPage()) {
            return $this->renderPrice($price, $this->getOmnibusInformation($product));
        }

        if (is_front_page() && Options::isShowOnFrontPage()) {
            return $this->renderPrice($price, $this->getOmnibusInformation($product));
        }

        if (is_page() && Options::isShowOnPage()) {
            return $this->renderPrice($price, $this->getOmnibusInformation($product));
        }

        return $price;
    }

    public function renderPrice($price, $omnibusHtml)
    {
        return $price . $omnibusHtml;
    }

    public static function getOmnibusInformation($product)
    {
        $lowestPriceCalculator = new LowestPriceCalculator();
        $historyPrice = $lowestPriceCalculator->getLowestPrice($product);

        if (!$historyPrice) {
            return '';
        }

        return (new Template())
            ->setPath(Plugin::getInstance()->basePath('templates/front/omnibus-price.php'))
            ->setParams([
                'historyPrice' => new FrontHistoryPriceViewModel($product, $historyPrice)
            ])->render();
    }

    public static function getVariationsOmnibusInformation($product)
    {
        if (!$product instanceof \WC_Product_Variable) {
            return '';
        }

        $historyPrices = [];

        foreach ($product->get_available_variations() as $variation) {
            if ($variationId = Arr::get($variation, 'variation_id')) {
                $lowestPriceCalculator = new LowestPriceCalculator();
                $historyPrices[] = new FrontHistoryPriceViewModel($product, $lowestPriceCalculator->getLowestPrice(wc_get_product($variationId)));
            }
        }

        return (new Template())
            ->setPath(Plugin::getInstance()->basePath('templates/front/omnibus-price-variations.php'))
            ->setParams([
                'productId' => $product->get_id(),
                'historyPrices' => $historyPrices
            ])->render();
    }
}