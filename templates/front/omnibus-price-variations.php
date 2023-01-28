<?php

/** @var $productId int */
/** @var $historyPrices \PerfectWPWCO\ViewModel\FrontHistoryPriceViewModel[] */

if (!defined('ABSPATH')) exit;

?>
<div class="pwp-omnibus-price__info">
    <?php foreach ($historyPrices as $historyPrice): ?>
        <div data-product-id="<?php echo $productId; ?>" data-variation-id="<?php echo $historyPrice->getId(); ?>" style="display: none;">
            <?php echo $historyPrice->getMessage(); ?>
        </div>
    <?php endforeach; ?>
</div>
