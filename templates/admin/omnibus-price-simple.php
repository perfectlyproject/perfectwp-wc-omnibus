<?php

/** @var $historyPrice \PerfectWPWCO\Models\HistoryPrice|null */

if (!defined('ABSPATH')) exit;

?>
<hr>
<?php
woocommerce_wp_text_input([
    'id' => '_last_price',
    'custom_attributes' => ['disabled' => 'disabled'],
    'value' => $historyPrice !== null ? $historyPrice->getPrice() : null,
    'data_type' => 'price',
    'label' => __('Omnibus - Price', 'perfectwp-wc-omnibus').' ('.get_woocommerce_currency_symbol().')',
    'desc_tip' => true,
    'description' => __('The lowest price in 30 days', 'perfectwp-wc-omnibus'),
]);

$range = '';

if ($historyPrice) {
    $from = $historyPrice->getStartDate()->format('Y-m-d H:i:s');
    $to = $historyPrice->getEndDate()->format('Y-m-d H:i:s');
    $range = implode(' - ', array_filter([$from, $to]));
}

woocommerce_wp_text_input([
    'id' => '_last_price_datetime',
    'custom_attributes' => ['disabled' => 'disabled'],
    'value' => $range,
    'data_type' => 'text',
    'label' => __('Omnibus - Date', 'perfectwp-wc-omnibus'),
    'desc_tip' => true,
    'description' => __('The date range when lowest price in 30 days occurred.', 'perfectwp-wc-omnibus'),
]);
?>