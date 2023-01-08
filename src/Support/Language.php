<?php

namespace PerfectWPWCO\Support;

if (!defined('ABSPATH')) exit;

class Language
{
    /**
     * Get current site language code
     *
     * @return string|null
     */
    public static function getCode()
    {
        if (function_exists('pll_current_language')) {
            return pll_current_language();
        }

        if (defined('ICL_SITEPRESS_VERSION')) {
            return apply_filters('wpml_current_language', null);
        }

        return null;
    }
}