<?php

namespace PerfectWPWCO\Providers;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Listeners\ProductListener;

class ListenerProvider
{
    public function boot()
    {
        $this->registerListeners();
    }

    public function registerListeners()
    {
        foreach ([
            ProductListener::class
                 ] as $class) {
            (new $class())->hooks();
        }
    }
}