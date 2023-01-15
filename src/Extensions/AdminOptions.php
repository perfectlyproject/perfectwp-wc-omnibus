<?php

namespace PerfectWPWCO\Extensions;

use PerfectWPWCO\Models\Options;
use PerfectWPWCO\Plugin;
use PerfectWPWCO\Repositories\HistoryPriceRepository;
use PerfectWPWCO\Support\Language;
use PerfectWPWCO\Utils\Arr;

class AdminOptions
{
    const SECTION_ID = 'pwp_wco_omnibus';

    public function boot()
    {
        add_filter('woocommerce_get_sections_products', [$this, 'filterWoocommerceGetSectionsProducts'], 200, 1);
        add_filter('woocommerce_get_settings_products', [$this, 'filterWoocommerceGetSettingsProducts'], 10, 2);
        add_action('plugin_action_links_' . plugin_basename(Plugin::getInstance()->getPluginPath()), [$this, 'filterPluginActionLinks'], 10, 1);
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
                'title' => __('Show on:', 'perfectwp-wc-omnibus'),
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
                'checkboxgroup' => '',
            ],
            [
                'desc' => __('Page', 'perfectwp-wc-omnibus'),
                'id' => Options::getOptionKey('is_show_on_page'),
                'type' => 'checkbox',
                'default' => 'no',
                'checkboxgroup' => 'end',
            ],
            [
                'title' => __('Number of days:', 'perfectwp-wc-omnibus'),
                'id' => Options::getOptionKey('lowest_price_number_of_days'),
                'type' => 'number',
                'default' => 30,
                'custom_attributes' => ['required' => 'required', 'min' => 1]
            ],
            [
                'title' => __('Message:', 'perfectwp-wc-omnibus'),
                'id' => Options::getTranslatedOptionKey('message_lowest_price', Language::getCode()),
                'type' => 'text',
                'default' => __('Lowest price [days] days before the discount: [price]', 'perfectwp-wc-omnibus'),
                'custom_attributes' => ['required' => 'required']
            ],
            [
                'title' => __('Show only for sale:', 'perfectwp-wc-omnibus'),
                'desc' => __('Yes (Recommended to enable this option)', 'perfectwp-wc-omnibus'),
                'id' => Options::getOptionKey('is_show_only_for_sale'),
                'type' => 'checkbox',
                'default' => 'yes',
            ],
            [
                'title' => __('Calculate include current price:', 'perfectwp-wc-omnibus'),
                'desc' => __('Yes (Recommended to disable this option)', 'perfectwp-wc-omnibus'),
                'id' => Options::getOptionKey('is_calculate_include_current_price'),
                'type' => 'checkbox',
                'default' => 'no',
            ],
            [
                'title' => __('Calculate lowest price from:', 'perfectwp-wc-omnibus'),
                'id' => Options::getOptionKey('calculate_lowest_price_from'),
                'type' => 'select',
                'options' => [
                    HistoryPriceRepository::FROM_LAST_CHANGED_PRICE_DATE => __('From last changed price date - Recommended', 'perfectwp-wc-omnibus'),
                    HistoryPriceRepository::FROM_NOW_DATE => __('From current date', 'perfectwp-wc-omnibus'),
                ],
                'default' => HistoryPriceRepository::FROM_LAST_CHANGED_PRICE_DATE,
            ],
            [
                'type' => 'sectionend'
            ],
        ];
    }

    public function filterPluginActionLinks($actions)
    {
        $actions['settings'] = sprintf(
            '<a href="%1$s" %2$s>%3$s</a>',
            admin_url('admin.php?page=wc-settings&tab=products&section=pwp_wco_omnibus'),
            'aria-label="' . __( 'Settings for Duplicate Post', 'perfectwp-wc-omnibus' ) . '"',
            esc_html__( 'Settings', 'perfectwp-wc-omnibus' )
        );

        return $actions;
    }
}