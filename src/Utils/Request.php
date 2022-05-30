<?php

namespace PerfectWPWCO\Utils;

if (!defined('ABSPATH')) exit;

class Request
{
    public static function get($key, $default = null)
    {
        return Arr::get($_GET, $key, $default);
    }

    public static function post($key, $default = null)
    {
        return Arr::get($_POST, $key, $default);
    }

    public static function request($key, $default = null)
    {
        return Arr::get($_REQUEST, $key, $default);
    }
}