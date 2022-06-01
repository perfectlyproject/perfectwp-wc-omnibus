<?php

namespace PerfectWPWCO;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Providers\AssetsProvider;
use PerfectWPWCO\Providers\ExtensionsProvider;
use PerfectWPWCO\Providers\ListenerProvider;
use PerfectWPWCO\Providers\SystemProvider;

final class Plugin extends Container
{
    /**
     * The Perfectly plugin version.
     *
     * @var string
     */
    const VERSION = '{VERSION}';

    /**
     * Plugin slug
     */
    const SLUG = 'pwp_wco';

    /**
     * Plugin textdomain
     */
    const TEXT_DOMAIN = 'perfectwp-wc-omnibus';

    /**
     * Application constructor.
     *
     * @param string $path
     */
    protected function __construct($path)
    {
        $this->setPluginPath($path);
        $this->loadHelpers();
        $this->setBasePath(dirname($path));
        $this->setBaseUrl(rtrim(plugin_dir_url($path), '/'));
    }

    /**
     * Create main instance
     *
     * @param string $path
     * @return \PerfectWPWCO\Plugin
     */
    public static function load($path)
    {
        if (is_null(static::$instance)) {
            $instance = static::setInstance(new self($path));
            $instance->init();

            return $instance;
        }
    }

    /**
     * Load helper functions
     */
    public function loadHelpers()
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'helpers.php';
    }

    /**
     * Init plugin
     */
    public function init()
    {
        $this->loadTextDomains();

        $this->registerBaseProviders();
    }

    /**
     * Load text domains
     */
    protected function loadTextDomains()
    {
        add_action('init', function() {
            $locale = determine_locale();
            $locale = apply_filters( 'plugin_locale', $locale, 'perfectwp-wc-omnibus' );

            load_textdomain( self::TEXT_DOMAIN, $this->basePath('languages/' . $locale . '.mo') );
            load_plugin_textdomain(self::TEXT_DOMAIN, false, $this->basePath('languages'));
        });
    }

    /**
     * Register base providers
     */
    private function registerBaseProviders()
    {
        $this->register(AssetsProvider::class);
        $this->register(SystemProvider::class);
        $this->register(ListenerProvider::class);
        $this->register(ExtensionsProvider::class);
    }

    /**
     * Register providers
     *
     * @param $provider
     * @return void
     */
    public function register($provider)
    {
        $provider = new $provider;
        $provider->boot();
    }

    /**
     * Get the container instance
     *
     * @return \PerfectWPWCO\Plugin
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * @param string $path
     */
    private function setPluginPath($path)
    {
        $this->instance('path.plugin_file', $path);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPluginPath()
    {
        return $this->get('path.plugin_file');
    }

    /**
     * Set base path
     *
     * @param string $path
     * @return $this
     */
    private function setBasePath($path)
    {
        $this->instance('path.base', $path);

        return $this;
    }

    /**
     * Get the path to the base plugin directory.
     *
     * @param string $path
     * @return string
     */
    public function basePath($path = '')
    {
        return $this->get('path.base').DIRECTORY_SEPARATOR.$path;
    }

    /**
     * Set base url
     *
     * @param string $path
     * @return \PerfectWPWCO\Plugin
     */
    public function setBaseUrl($path)
    {
        $this->instance('url.base', $path);

        return $this;
    }

    /**
     * Return base url
     *
     * @param string $path
     * @return string
     */
    public function baseUrl($path)
    {
        return $this->get('url.base').'/'.$path;
    }

    /**
     * Return public url
     *
     * @param string $path
     * @return string
     */
    public function publicUrl($path)
    {
        return $this->baseUrl('public/'.$path);
    }
}