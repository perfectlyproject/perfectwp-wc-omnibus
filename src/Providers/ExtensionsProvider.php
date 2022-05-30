<?php

namespace PerfectWPWCO\Providers;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Extensions\ProductAdditionalFields;
use PerfectWPWCO\Extensions\ProductPageAdditionalInfo;

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
                 ] as $class) {
            (new $class())->boot();
        }
    }
}