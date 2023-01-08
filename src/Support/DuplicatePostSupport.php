<?php

namespace PerfectWPWCO\Support;

use PerfectWPWCO\Listeners\ProductListener;
use PerfectWPWCO\Managers\ListenerManager;

class DuplicatePostSupport
{
    private $listenerManager;

    public function __construct(ListenerManager $listenerManager)
    {
        $this->listenerManager = $listenerManager;
    }

    public function boot()
    {
        add_action('woocommerce_product_duplicate_before_save', [$this, 'hookDuplicatePostPreCopy'], 10);
        add_action('woocommerce_product_duplicate', [$this, 'hookDuplicatePostPostCopy'], 10);
    }

    public function hookDuplicatePostPreCopy()
    {
        $this->listenerManager->unregister(ProductListener::class);
    }

    public function hookDuplicatePostPostCopy()
    {
        $this->listenerManager->register(ProductListener::class);
    }
}