<?php

namespace PerfectWPWCO\System;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Plugin;

class System
{
    public static function getPluginVersion()
    {
        return Plugin::VERSION;
    }

    public static function getDBVersion()
    {
        return get_option(Plugin::SLUG . '_db_version', false);
    }
}