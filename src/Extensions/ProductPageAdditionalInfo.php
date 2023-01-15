<?php

namespace PerfectWPWCO\Extensions;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\LowestPriceCalculator;
use PerfectWPWCO\Models\Options;
use PerfectWPWCO\Plugin;
use PerfectWPWCO\Repositories\HistoryPriceRepository;
use PerfectWPWCO\Utils\Template;
use PerfectWPWCO\ViewModel\FrontHistoryPriceViewModel;

class ProductPageAdditionalInfo
{
    public function boot()
    {
        add_action('woocommerce_get_price_html', [$this, 'filterGetPriceHtml'], 10, 2);
    }

    public function filterGetPriceHtml($price, $product)
    {
        if (is_admin() || $product instanceof \WC_Product_Variable || (!$product->is_on_sale() && Options::isShowOnlyForSale())) {
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
}