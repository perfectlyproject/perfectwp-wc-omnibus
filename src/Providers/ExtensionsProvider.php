<?php

namespace PerfectWPWCO\Providers;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Extensions\AdminOptions;
use PerfectWPWCO\Extensions\ProductAdditionalFields;
use PerfectWPWCO\Extensions\ProductPageAdditionalInfo;
use PerfectWPWCO\Extensions\Shortcode;
use PerfectWPWCO\Plugin;
use PerfectWPWCO\Support\DuplicatePostSupport;

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
            Shortcode::class,
            DuplicatePostSupport::class
                 ] as $class) {
            Plugin::getInstance()->get($class)->boot();
        }
    }
}