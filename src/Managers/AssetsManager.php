<?php

namespace PerfectWPWCO\Managers;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Assets\AbstractAsset;
use PerfectWPWCO\Assets\Script;
use PerfectWPWCO\Assets\Style;
use PerfectWPWCO\Utils\Arr;

class AssetsManager
{
    /**
     * List of localizes provides to javascript global variable
     *
     * @var array
     */
    private $localizeScript = [];

    /**
     * List of scripts to enqueue
     *
     * @var array<Script>
     */
    private $scripts = [];

    /**
     * List of styles to enqueue
     *
     * @var array<Style>
     */
    private $styles = [];

    /**
     * Add image size
     *
     * @param string $name
     * @param int $width
     * @param int $height
     * @param bool $crop
     */
    public function registerImageSize($name, $width, $height, $crop = false)
    {
        add_image_size($name, $width, $height, $crop);
    }

    /**
     * Return registered image sizes
     *
     * @return mixed
     */
    public function getImageSizes()
    {
        global $_wp_additional_image_sizes;
        return $_wp_additional_image_sizes;
    }

    /**
     * Register style
     *
     * @param \PP\Assets\Style $style
     */
    public function registerStyle(Style $style)
    {

        Arr::set($this->styles, $style->getHandle(), $style);
    }

    /**
     * Register script
     *
     * @param \PP\Assets\Script $script
     */
    public function registerScript(Script $script)
    {

        Arr::set($this->scripts, $script->getHandle(), $script);
    }

    /**
     * Return list scripts by type
     *
     * @param null $viewType
     * @return array
     * @throws \Exception
     */
    public function getScripts($viewType = null)
    {
        return array_filter($this->scripts, function(Script $script) use ($viewType) {
            return $script->getViewType() === $viewType;
        });
    }

    /**
     * Return list styles by type
     *
     * @param string|null $viewType
     * @return array
     */
    public function getStyles($viewType = null)
    {
        return array_filter($this->styles, function(Style $style) use ($viewType) {
            return $style->getViewType() === $viewType;
        });
    }

    /**
     * Return list scripts for front
     *
     * @return array
     * @throws \Exception
     */
    public function getFrontScripts()
    {
        return $this->getScripts(AbstractAsset::VIEW_TYPE_FRONT);
    }

    /**
     * Return list scripts for admin
     *
     * @return array
     * @throws \Exception
     */
    public function getAdminScripts()
    {
        return $this->getScripts(AbstractAsset::VIEW_TYPE_ADMIN);
    }

    /**
     * Return list scripts for gutenberg blocks
     *
     * @return array
     * @throws \Exception
     */
    public function getGutenbergScripts()
    {
        return $this->getScripts(AbstractAsset::VIEW_TYPE_GUTENBERG);
    }

    /**
     * Return list styles for front
     *
     * @return array
     * @throws \Exception
     */
    public function getFrontStyles()
    {
        return $this->getStyles(AbstractAsset::VIEW_TYPE_FRONT);
    }

    /**
     * Return list styles for admin
     *
     * @return array
     * @throws \Exception
     */
    public function getAdminStyles()
    {
        return $this->getStyles(AbstractAsset::VIEW_TYPE_ADMIN);
    }

    /**
     * Return list scripts for gutenberg blocks
     *
     * @return array
     */
    public function getGutenbergStyles()
    {
        return $this->getStyles(AbstractAsset::VIEW_TYPE_GUTENBERG);
    }

    /**
     * Add params to js data object
     *
     * @param $array
     */
    public function addJsData(array $array)
    {

        $dots = Arr::dot($array);

        foreach ($dots as $key => $value) {
            Arr::set($this->localizeScript, 'data.' . $key, $value);
        }
    }

    /**
     * Add js translation
     *
     * @param $array
     */
    public function addJsTranslation(array $array)
    {

        $dots = Arr::dot($array);

        foreach ($dots as $key => $value) {
            Arr::set($this->localizeScript, 'translations.' . $key, $value);
        }
    }

    /**
     * Return list all localizes
     *
     * @return array
     */
    public function getLocalizeScript(): array
    {
        return $this->localizeScript;
    }
}