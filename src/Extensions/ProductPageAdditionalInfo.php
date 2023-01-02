<?php

namespace PerfectWPWCO\Extensions;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Models\Options;
use PerfectWPWCO\Plugin;
use PerfectWPWCO\Repositories\HistoryPriceRepository;
use PerfectWPWCO\Utils\Template;
use PerfectWPWCO\ViewModel\HistoryPriceViewModel;

class ProductPageAdditionalInfo
{
    public function boot()
    {
        add_action('woocommerce_get_price_html', [$this, 'filterGetPriceHtml'], 10, 2);
    }

    public function filterGetPriceHtml($price, $product)
    {
        if (!$product->is_on_sale() || is_admin()) {
            return $price;
        }

        if (Options::isShowOnProductPage()
            && is_single()
            && (get_queried_object_id() === $product->get_id() || get_queried_object_id() === $product->get_parent_id())) {
            return $this->renderPrice($price, $this->getOmnibusInformation($product));
        }

        if (Options::isShowOnArchivePage() && is_archive() && !is_tax()) {
            return $this->renderPrice($price, $this->getOmnibusInformation($product));
        }

        if (Options::isShowOnTaxonomyPage() && is_tax()) {
            return $this->renderPrice($price, $this->getOmnibusInformation($product));
        }

        if (Options::isShowOnFrontPage() && is_front_page()) {
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
        $historyPriceRepository = new HistoryPriceRepository();
        $historyPrice = $historyPriceRepository->findLowestHistoryPriceIn30Days($product->get_id());

        if (!$historyPrice) {
            return '';
        }

        return (new Template())
            ->setPath(Plugin::getInstance()->basePath('templates/front/omnibus-price.php'))
            ->setParams([
                'historyPrice' => new HistoryPriceViewModel($product, $historyPrice)
            ])->render();
    }
}