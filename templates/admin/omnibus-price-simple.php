<?php

if (! defined('ABSPATH')) {
    exit;
}

?>

    <hr>

<?php

woocommerce_wp_text_input([
        'id' => '_last_price',
        'custom_attributes' => ['disabled' => 'disabled'],
        'value' => $historyPrice !== null ? $historyPrice->getPrice() : null,
        'data_type' => 'price',
        'label' => __('Omnibus - Price', 'pwp-wco').' ('.get_woocommerce_currency_symbol().')',
        'desc_tip' => true,
        'description' => __('The lowest price in 30 days', 'pwp-wco'),
    ]);

woocommerce_wp_text_input([
        'id' => '_last_price_datetime',
        'custom_attributes' => ['disabled' => 'disabled'],
        'value' => $historyPrice !== null ? $historyPrice->getDate()->format('Y-m-d') : null,
        'data_type' => 'text',
        'label' => __('Omnibus - Date', 'pwp-wco'),
        'desc_tip' => true,
        'description' => __('The date when lowest price in 30 days occurred.', 'pwp-wco'),
    ]);

?>