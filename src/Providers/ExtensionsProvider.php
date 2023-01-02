<?php

namespace PerfectWPWCO\Providers;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Extensions\AdminOptions;
use PerfectWPWCO\Extensions\ProductAdditionalFields;
use PerfectWPWCO\Extensions\ProductPageAdditionalInfo;
use PerfectWPWCO\Extensions\Shortcode;

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
            Shortcode::class
                 ] as $class) {
            (new $class())->boot();
        }
    }
}