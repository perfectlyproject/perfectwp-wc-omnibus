<?php

namespace PerfectWPWCO\System;

use PerfectWPWCO\Models\Options;
use PerfectWPWCO\Plugin;

class Migrations
{
    public function migrate($currentVersion)
    {
        $reflectionClass = new \ReflectionClass($this);

        if (!$currentVersion) {
            $currentVersion = 0;
        }

        $currentVersion = (int) str_replace('.', '', $currentVersion);

        foreach ($reflectionClass->getMethods() as $method) {
            if (strpos($method->getName(), 'update') === 0) {
                $updateVersion = (int) str_replace('_', '', str_replace('update', '', $method->getName()));

                if ($updateVersion > $currentVersion) {
                    var_dump($method->getName());
                    call_user_func([$this, $method->getName()]);
                }
            }
        }
    }

    public function update1_0_2()
    {
        Options::update('is_show_on_product_page', 'yes');
        Options::update('is_show_on_archive_page', 'no');
        Options::update('is_show_on_front_page', 'no');
        Options::update('is_show_on_tax_page', 'no');

        update_option(Plugin::SLUG . '_db_version', '1.0.2');
    }
}