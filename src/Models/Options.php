<?php

namespace PerfectWPWCO\Models;

use PerfectWPWCO\Plugin;
use PerfectWPWCO\Repositories\HistoryPriceRepository;
use PerfectWPWCO\Support\Language;

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

    private static function getTranslatedOption($key, $default = null, $lang = null)
    {
        $option = get_option(self::getTranslatedOptionKey($key, $lang));

        if ($option === false) {
            return $default;
        }

        return $option;
    }

    public static function getOptionKey(string $key, $lang = null)
    {
        return Plugin::SLUG . '_' . implode('_', array_filter([$key, $lang]));
    }

    public static function getTranslatedOptionKey(string $key, $lang = null)
    {
        if (!$lang) {
            $lang = Language::getCode();
        }

        return self::getOptionKey($key, $lang);
    }

    public static function update($key, $value)
    {
        update_option(self::getOptionKey($key), $value);
    }

    /**
     * @return bool
     */
    public static function isShowOnProductPage(): bool
    {
        return self::getOption('is_show_on_product_page', 'yes') === 'yes';
    }

    /**
     * @return bool
     */
    public static function isShowOnArchivePage(): bool
    {
        return self::getOption('is_show_on_archive_page', 'no') === 'yes';
    }

    /**
     * @return bool
     */
    public static function isShowOnFrontPage(): bool
    {
        return self::getOption('is_show_on_front_page', 'no') === 'yes';
    }

    /**
     * @return bool
     */
    public static function isShowOnTaxonomyPage(): bool
    {
        return self::getOption('is_show_on_tax_page', 'no') === 'yes';
    }

    /**
     * @return bool
     */
    public static function isShowOnPage(): bool
    {
        return self::getOption('is_show_on_page', 'no') === 'yes';
    }

    /**
     * @return int
     */
    public static function getLowestPriceNumberOfDays(): int
    {
        return max(1, (int) self::getOption('lowest_price_number_of_days', 30));
    }

    /**
     * @return bool
     */
    public static function isCalculateIncludeCurrentPrice(): bool
    {
        return self::getOption('is_calculate_include_current_price', 'no') === 'yes';
    }

    /**
     * @return string
     */
    public static function getCalculateLowestPriceFrom(): string
    {
        return self::getOption('calculate_lowest_price_from', HistoryPriceRepository::FROM_LAST_CHANGED_PRICE_DATE);
    }

    /**
     * @return bool
     */
    public static function isShowOnlyForSale(): bool
    {
        return self::getOption('is_show_only_for_sale', 'yes') === 'yes';
    }

    /**
     * @return string
     */
    public static function getMessageLowestPrice(): string
    {
        return self::getTranslatedOption('message_lowest_price', __('Lowest price [days] days before the discount: [price]', 'perfectwp-wc-omnibus'));
    }
}