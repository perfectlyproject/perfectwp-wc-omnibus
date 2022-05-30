<?php

namespace PerfectWPWCO\System;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Models\HistoryPrice;
use PerfectWPWCO\Plugin;

class Uninstaller
{
    public static function uninstall()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        self::dropTables();
        self::deleteMetaData();
    }

    private static function dropTables()
    {
        global $wpdb;

        $wpdb->query('DROP TABLE '. HistoryPrice::getPrefixedTable());
    }

    public static function deleteMetaData()
    {
        delete_option(Plugin::SLUG . '_db_version');
    }
}