<?php

/** @var $historyPrice \PerfectWPWCO\Models\HistoryPrice */

if (!defined('ABSPATH')) exit;

?>
<div class="pwp-omnibus-price__info" style="font-size: 12px">
    <?php printf(__('The lowest price in 30 days: %s', 'perfectwp-wc-omnibus'), wc_price($historyPrice->getPrice())); ?>
</div>
