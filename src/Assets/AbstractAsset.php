<?php

namespace PerfectWPWCO\Assets;

if (!defined('ABSPATH')) exit;

abstract class AbstractAsset
{
    CONST VIEW_TYPE_FRONT = 'front';
    CONST VIEW_TYPE_ADMIN = 'admin';
    CONST VIEW_TYPE_GUTENBERG = 'gutenberg';

    /**
     * @var string
     */
    private $handle;

    /**
     * @var string
     */
    private $src = null;

    /**
     * @var array
     */
    private $deps = [];

    /**
     * @var string
     */
    private $ver = '';

    /**
     * @var string
     */
    private $viewType = self::VIEW_TYPE_FRONT;

    /**
     * AbstractAsset constructor.
     *
     * @param string|null $handle
     */
    public function __construct($handle = null)
    {
        $this->handle = $handle;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param string $handle
     * @return \PerfectWPWCO\Assets\AbstractAsset
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;

        return $this;
    }

    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @param string $src
     * @return \PerfectWPWCO\Assets\AbstractAsset
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * @return array
     */
    public function getDeps()
    {
        return $this->deps;
    }

    /**
     * @param array $deps
     * @return \PerfectWPWCO\Assets\AbstractAsset
     */
    public function setDeps($deps)
    {
        $this->deps = $deps;

        return $this;
    }

    /**
     * @return string
     */
    public function getVer()
    {
        return $this->ver;
    }

    /**
     * @param string $ver
     * @return \PerfectWPWCO\Assets\AbstractAsset
     */
    public function setVer($ver)
    {
        $this->ver = $ver;

        return $this;
    }

    /**
     * @return string
     */
    public function getViewType()
    {
        return $this->viewType;
    }

    /**
     * Set view type
     *
     * @param string $viewType
     * @return \PerfectWPWCO\Assets\AbstractAsset
     */
    public function setViewType($viewType)
    {
        if (!in_array($viewType, [self::VIEW_TYPE_FRONT, self::VIEW_TYPE_ADMIN, self::VIEW_TYPE_GUTENBERG])) {
            throw new \Exception('Invalid type');
        }

        $this->viewType = $viewType;

        return $this;
    }

    /**
     * Enqueue asset
     */
    abstract public function enqueue();
}