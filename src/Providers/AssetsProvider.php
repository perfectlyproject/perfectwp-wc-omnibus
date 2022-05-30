<?php

namespace PerfectWPWCO\Providers;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Managers\AssetsManager;
use PerfectWPWCO\Plugin;
use PerfectWPWCO\Utils\Arr;

class AssetsProvider
{
    public function boot()
    {
        Plugin::getInstance()->instance('assets', new AssetsManager());

        $this->registerHooks();
        $this->initLocalizes();
        $this->initPluginAssets();
    }

    public function initLocalizes()
    {
        Plugin::getInstance()->get('assets')->addJsData([
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'locale' => get_locale(),
            'lang' => substr(get_locale(), 0, 2)
        ]);
    }

    public function initPluginAssets()
    {
        $pluginAssets = Plugin::getInstance()->basePath('public'.DIRECTORY_SEPARATOR.'plugin-assets.php');

        if (!file_exists($pluginAssets)) {
            return;
        }

        $assets = require_once $pluginAssets;

        foreach (Arr::get($assets, 'styles') as $style) {
            Plugin::getInstance()->get('assets')->registerStyle($style);
        }

        foreach (Arr::get($assets, 'scripts') as $script) {
            Plugin::getInstance()->get('assets')->registerScript($script);
        }
    }

    /**
     * Add action and filters
     */
    public function registerHooks()
    {
        add_action('admin_init', [$this, 'actionAdminEnqueueScripts'], 10, 0);
        add_action('wp_enqueue_scripts', [$this, 'actionWpEnqueueScripts'], 10, 0);
        add_action('wp_footer', [$this, 'hookActionWpFooter'], 0, 0);
        add_action('admin_footer', [$this, 'hookActionWpFooter'], 0, 0);
        add_action('enqueue_block_assets', [$this, 'actionGutenbergEnqueueScripts']);
    }

    /**
     * Wordpress core action admin_enqueue_scripts
     *
     * https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
     */
    public function actionAdminEnqueueScripts()
    {
        foreach (Plugin::getInstance()->get('assets')->getAdminScripts() as $script) {
            $script->enqueue();
        }

        foreach (Plugin::getInstance()->get('assets')->getAdminStyles() as $style) {
            $style->enqueue();
        }
    }

    /**
     * Wordpress core action enqueue_block_assets
     */
    public function actionGutenbergEnqueueScripts()
    {
        foreach (Plugin::getInstance()->get('assets')->getGutenbergScripts() as $script) {
            $script->enqueue();
        }

        foreach (Plugin::getInstance()->get('assets')->getGutenbergStyles() as $style) {
            $style->enqueue();
        }
    }

    /**
     * Wordpress core action wp_enqueue_scripts
     *
     * https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
     */
    public function actionWpEnqueueScripts()
    {
        foreach (Plugin::getInstance()->get('assets')->getFrontScripts() as $script) {
            $script->enqueue();
        }

        foreach (Plugin::getInstance()->get('assets')->getFrontStyles() as $style) {
            $style->enqueue();
        }
    }

    /**
     * Add js the localizes data to footer in json format
     *
     * Action https://codex.wordpress.org/Plugin_API/Action_Reference/wp_footer
     * Append script to the footer https://codex.wordpress.org/Function_Reference/wp_footer
     */
    public function hookActionWpFooter()
    {
        echo '<script type="text/javascript">window.wppd = ' . json_encode(Plugin::getInstance()->get('assets')->getLocalizeScript()) . '</script>';
    }
}