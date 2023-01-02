<?php

namespace PerfectWPWCO\Models;

use PerfectWPWCO\Plugin;

class Options
{
    public static function updateOptions($options)
    {
        foreach ($options as $option => $value) {
            self::update($option, $value);
        }
    }

    private static function getOption($key, $default = null)
    {
        $option = get_option(self::getOptionKey($key));

        if ($option === false) {
            return $default;
        }

        return $option;
    }

    public static function getOptionKey($key)
    {
        return Plugin::SLUG . '_' . $key;
    }

    public static function update($key, $value)
    {
        update_option(self::getOptionKey($key), $value);
    }

    /**
     * @return bool
     */
    public static function isShowOnProductPage()
    {
        return self::getOption('is_show_on_product_page', 'yes') === 'yes';
    }

    /**
     * @return bool
     */
    public static function isShowOnArchivePage()
    {
        return self::getOption('is_show_on_archive_page', 'no') === 'yes';
    }

    /**
     * @return bool
     */
    public static function isShowOnFrontPage()
    {
        return self::getOption('is_show_on_front_page', 'no') === 'yes';
    }

    /**
     * @return bool
     */
    public static function isShowOnTaxonomyPage()
    {
        return self::getOption('is_show_on_tax_page', 'no') === 'yes';
    }
}