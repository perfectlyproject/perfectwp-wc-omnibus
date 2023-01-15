<?php

/** @var $historyPrice \PerfectWPWCO\Models\HistoryPrice|null */
/** @var $productId int */

use PerfectWPWCO\Models\Options;

if (!defined('ABSPATH')) exit;

?>
<hr>

<div class="pwo-modal" id="history-prices-<?php echo $productId; ?>">
    <div class="pwo-modal__inner">
        <div class="pwo-modal__title"><?php _e('Price history', 'perfectwp-wc-omnibus'); ?></div>
        <button type="button" data-action="modal-close" class="pwo-modal__btn-close dashicons dashicons-no"></button>

        <?php if (!empty($historyPrices)): ?>
            <table class="widefat striped fixed">
                <thead>
                <tr>
                    <th class="manage-column column-thumb"><?php _e('Start Date', 'perfectwp-wc-omnibus'); ?></th>
                    <th class="manage-column column-thumb"><?php _e('End Date', 'perfectwp-wc-omnibus'); ?></th>
                    <th class="manage-column column-thumb"><?php _e('Price', 'perfectwp-wc-omnibus'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($historyPrices as $historyPrice): ?>
                    <tr>
                        <td><?php echo wp_date('Y-m-d H:i:s', $historyPrice->getStartDate()->getTimestamp()); ?></td>
                        <td><?php echo $historyPrice->getEndDate() ? wp_date('Y-m-d H:i:s', $historyPrice->getEndDate()->getTimestamp()) : '-'; ?></td>
                        <td><?php echo wc_price($historyPrice->getPrice()); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="inline notice notice-warning" style="padding: 10px 15px">
                <?php _e('The price history is empty.', 'perfectwp-wc-omnibus'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!$lowestHistoryPrice): ?>
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
    'value' => $lowestHistoryPrice !== null ? $lowestHistoryPrice->getPrice() : null,
    'data_type' => 'price',
    'label' => __('Omnibus - Price', 'perfectwp-wc-omnibus').' ('.get_woocommerce_currency_symbol().')',
    'desc_tip' => true,
    'description' => sprintf(__('The lowest price in %d days', 'perfectwp-wc-omnibus'), Options::getLowestPriceNumberOfDays()),
]);

$range = '';

if ($lowestHistoryPrice) {
    $from = wp_date('Y-m-d H:i:s', $lowestHistoryPrice->getStartDate()->getTimestamp());
    $to = $lowestHistoryPrice->getEndDate() ? wp_date('Y-m-d H:i:s', $lowestHistoryPrice->getEndDate()->getTimestamp()) : null;
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

<div class="pwo-edit-product-box">
    <button type="button" data-action="modal-open" data-modal-target="#history-prices-<?php echo $productId; ?>" class="button button-primary button-small"><?php _e('Show Price History', 'perfectwp-wc-omnibus'); ?></button>
</div>
