<?php

namespace PerfectWPWCO\Providers;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Listeners\ProductListener;
use PerfectWPWCO\Managers\ListenerManager;
use PerfectWPWCO\Plugin;

class ListenerProvider
{
    private $listeners = [
        ProductListener::class
    ];

    public function boot()
    {
        Plugin::getInstance()->bind('listener.manager', function () {
            return new ListenerManager(Plugin::getInstance());
        });

        $this->registerListeners();
    }

    public function registerListeners()
    {
        foreach ($this->listeners as $listener) {
            Plugin::getInstance()->get('listener.manager')->register($listener);
        }
    }
}