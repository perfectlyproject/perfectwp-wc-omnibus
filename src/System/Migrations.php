<?php

namespace PerfectWPWCO\System;

use PerfectWPWCO\Models\HistoryPrice;
use PerfectWPWCO\Models\Options;
use PerfectWPWCO\Plugin;

class Migrations
{
    public function migrate($currentVersion)
    {
        $reflectionClass = new \ReflectionClass($this);

        if (!$currentVersion) {
            $currentVersion = '1.0.0';
        }

        foreach ($reflectionClass->getMethods() as $method) {
            if (strpos($method->getName(), 'update') === 0) {
                $updateVersion = str_replace('_', '.', str_replace('update', '', $method->getName()));

                if (version_compare($currentVersion, $updateVersion, '<')) {
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

    public function update1_0_4()
    {
        global $wpdb;

        $wpdb->query('ALTER TABLE '.HistoryPrice::getPrefixedTable().' CHANGE `date` `start_date` datetime NOT NULL');
        $wpdb->query('ALTER TABLE '.HistoryPrice::getPrefixedTable().' ADD `end_date` datetime NULL DEFAULT NULL');

        $wpdb->query('UPDATE '.HistoryPrice::getPrefixedTable().' SET end_date = start_date;');

        Options::update('lowest_price_number_of_days', '30');
        Options::update('is_calculate_include_current_price', 'no');
        Options::update('is_show_only_for_sale', 'yes');

        update_option(Plugin::SLUG . '_db_version', '1.0.4');
    }
}