<?php

use PerfectWPWCO\Models\Options;

/** @var $historyPrice \PerfectWPWCO\ViewModel\HistoryPriceViewModel */

if (!defined('ABSPATH')) exit;

?>
<div class="pwp-omnibus-price__info" style="font-size: 12px">
    <?php printf(__('The lowest price in %d days: %s', 'perfectwp-wc-omnibus'), Options::getLowestPriceNumberOfDays(), wc_price($historyPrice->getPrice())); ?>
</div>
