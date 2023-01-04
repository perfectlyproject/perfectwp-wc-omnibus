<?php

namespace PerfectWPWCO\System;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Models\HistoryPrice;
use PerfectWPWCO\Plugin;

class Installer
{
    public function install()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $this->createTables();
    }

    private function createTables()
    {
        global $wpdb;

        dbDelta('CREATE TABLE IF NOT EXISTS '.HistoryPrice::getPrefixedTable().' (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `product_id` bigint(20) NOT NULL,
          `price` decimal(19,4) NOT NULL,
          `start_date` datetime NOT NULL,
          `end_date` datetime NULL default NULL,
          PRIMARY KEY (`id`)
        )' . $wpdb->get_charset_collate());

        update_option(Plugin::SLUG . '_db_version', System::getPluginVersion());
    }
}