<?php

use PerfectWPWCO\Models\Options;

/** @var $historyPrice \PerfectWPWCO\ViewModel\FrontHistoryPriceViewModel */

if (!defined('ABSPATH')) exit;

?>
<div class="pwp-omnibus-price__info" style="font-size: 12px"><?php echo $historyPrice->getMessage(); ?></div>
