<?php

namespace PerfectWPWCO\Extensions;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Plugin;
use PerfectWPWCO\Repositories\HistoryPriceRepository;
use PerfectWPWCO\Utils\Template;

class ProductPageAdditionalInfo
{
    private $repositoryHistoryPrice;

    public function __construct()
    {
        $this->repositoryHistoryPrice = new HistoryPriceRepository();
    }

    public function boot()
    {
        add_action('woocommerce_get_price_html', [$this, 'filterGetPriceHtml'], 10, 2);
    }

    public function filterGetPriceHtml($price, $product)
    {
        $productId = intval($product->get_id());

        if (!$product->is_on_sale()) {
            return $price;
        }

        $historyPrice = $this->repositoryHistoryPrice->findLowestHistoryPriceIn30Days($productId);

        if ($historyPrice === null) {
            return $price;
        }

        $omnibusHtml = (new Template())
            ->setPath(Plugin::getInstance()->basePath('templates/front/omnibus-price.php'))
            ->setParams([
                'historyPrice' => $historyPrice
            ])->render();

        return $price . $omnibusHtml;
    }
}