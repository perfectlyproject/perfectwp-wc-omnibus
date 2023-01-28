<?php

namespace PerfectWPWCO\Providers;

use PerfectWPWCO\Contracts\Shortcode;
use PerfectWPWCO\Shortcodes\OmnibusInformationShortcode;
use PerfectWPWCO\Plugin;
use PerfectWPWCO\Shortcodes\OmnibusInformationShortcodeVariations;

class ShortcodeProvider
{
    /**
     * @var string[]
     */
    private $shortcodes = [
        OmnibusInformationShortcode::class,
        OmnibusInformationShortcodeVariations::class
    ];

    public function boot()
    {
        foreach ($this->shortcodes as $shortcodeClass) {
            $this->registerShortcode($this->resolveShortcode($shortcodeClass));
        }
    }

    public function registerShortcode(Shortcode $shortcode)
    {
        add_shortcode(Plugin::SLUG . '_' . $shortcode->getName(), function ($attrs) use ($shortcode) {
            return apply_filters(Plugin::SLUG . '_shortcode_' . $shortcode->getName(), $shortcode->render($attrs), $attrs);
        });
    }

    public function resolveShortcode(string $shortcodeClass): Shortcode
    {
        return Plugin::getInstance()->get($shortcodeClass);
    }
}