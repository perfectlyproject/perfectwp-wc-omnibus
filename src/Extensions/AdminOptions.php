<?php

namespace PerfectWPWCO\Extensions;

use PerfectWPWCO\Models\Options;

class AdminOptions
{
    const SECTION_ID = 'pwp_wco_omnibus';

    public function boot()
    {
        add_filter('woocommerce_get_sections_products', [$this, 'filterWoocommerceGetSectionsProducts'], 200, 1);
        add_filter('woocommerce_get_settings_products', [$this, 'filterWoocommerceGetSettingsProducts'], 10, 2);
    }

    public function filterWoocommerceGetSectionsProducts($sections)
    {
        $sections[self::SECTION_ID] = __('Omnibus Directive', 'perfectwp-wc-omnibus');

        return $sections;
    }

    public function filterWoocommerceGetSettingsProducts($settings, $currentSection)
    {
        if ($currentSection !== self::SECTION_ID) {
            return $settings;
        }

        return [
            [
                'title' => __('Omnibus Directive', 'perfectwp-wc-omnibus'),
                'type' => 'title',
                'id' => self::SECTION_ID
            ],
            [
                'title' => __('Show on', 'perfectwp-wc-omnibus'),
                'desc' => __('Single product page', 'perfectwp-wc-omnibus'),
                'id' => Options::getOptionKey('is_show_on_product_page'),
                'type' => 'checkbox',
                'default' => 'yes',
                'checkboxgroup' => 'start',
            ],
            [
                'desc' => __('Archive page', 'perfectwp-wc-omnibus'),
                'id' => Options::getOptionKey('is_show_on_archive_page'),
                'type' => 'checkbox',
                'default' => 'no',
                'checkboxgroup' => '',
            ],
            [
                'desc' => __('Taxonomy page', 'perfectwp-wc-omnibus'),
                'id' => Options::getOptionKey('is_show_on_tax_page'),
                'type' => 'checkbox',
                'default' => 'no',
                'checkboxgroup' => '',
            ],
            [
                'desc' => __('Front page', 'perfectwp-wc-omnibus'),
                'id' => Options::getOptionKey('is_show_on_front_page'),
                'type' => 'checkbox',
                'default' => 'no',
                'checkboxgroup' => 'end',
            ],
            [
                'title' => __('Number of days', 'perfectwp-wc-omnibus'),
                'id' => Options::getOptionKey('lowest_price_number_of_days'),
                'type' => 'number',
                'default' => 30,
                'custom_attributes' => ['required' => 'required', 'min' => 1]
            ],
            [
                'type' => 'sectionend'
            ],
        ];
    }
}