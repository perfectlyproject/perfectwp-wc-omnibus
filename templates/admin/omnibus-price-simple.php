<?php

/** @var $historyPrice \PerfectWPWCO\Models\HistoryPrice|null */

use PerfectWPWCO\Models\Options;

if (!defined('ABSPATH')) exit;

?>
<hr>
<?php if (!$hasHistoryPrice): ?>
<div class="form-field" style="margin: 0 15px;">
    <div class="inline notice notice-warning" style="padding: 10px 15px">
        <?php _e('The below lowest price has not been found in the history table, price is generated automatically and can not be sure.', 'perfectwp-wc-omnibus'); ?>
    </div>
</div>
<?php endif; ?>

<?php

woocommerce_wp_text_input([
    'id' => '_last_price',
    'custom_attributes' => ['disabled' => 'disabled'],
    'value' => $historyPrice !== null ? $historyPrice->getPrice() : null,
    'data_type' => 'price',
    'label' => __('Omnibus - Price', 'perfectwp-wc-omnibus').' ('.get_woocommerce_currency_symbol().')',
    'desc_tip' => true,
    'description' => sprintf(__('The lowest price in %d days', 'perfectwp-wc-omnibus'), Options::getLowestPriceNumberOfDays()),
]);

$range = '';

if ($historyPrice) {
    $from = wp_date('Y-m-d H:i:s', $historyPrice->getStartDate()->getTimestamp());
    $to = $historyPrice->getEndDate() ? wp_date('Y-m-d H:i:s', $historyPrice->getEndDate()->getTimestamp()) : null;
    $range = implode(' - ', array_filter([$from, $to]));
}

woocommerce_wp_text_input([
    'id' => '_last_price_datetime',
    'custom_attributes' => ['disabled' => 'disabled'],
    'value' => $range,
    'data_type' => 'text',
    'label' => __('Omnibus - Date', 'perfectwp-wc-omnibus'),
    'desc_tip' => true,
    'description' => sprintf(__('The date range when lowest price in %d days occurred.', 'perfectwp-wc-omnibus'), Options::getLowestPriceNumberOfDays()),
]);
?>