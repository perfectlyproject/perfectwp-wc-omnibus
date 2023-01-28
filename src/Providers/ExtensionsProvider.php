<?php

namespace PerfectWPWCO\Providers;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Extensions\AdminOptions;
use PerfectWPWCO\Extensions\ProductAdditionalFields;
use PerfectWPWCO\Extensions\ProductPageAdditionalInfo;
use PerfectWPWCO\Extensions\ReindexHistoryPriceCron;
use PerfectWPWCO\Plugin;
use PerfectWPWCO\Support\DuplicatePostSupport;
use PerfectWPWCO\Support\WooDiscountRulesPluginSupport;

class ExtensionsProvider
{
    public function boot()
    {
        $this->registerFields();
    }

    public function registerFields()
    {
        foreach ([
            ProductAdditionalFields::class,
            ProductPageAdditionalInfo::class,
            AdminOptions::class,
            DuplicatePostSupport::class,
            WooDiscountRulesPluginSupport::class,
            ReindexHistoryPriceCron::class,
                 ] as $class) {
            Plugin::getInstance()->get($class)->boot();
        }
    }
}