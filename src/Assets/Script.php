<?php

namespace PerfectWPWCO\Assets;

if (!defined('ABSPATH')) exit;

class Script extends AbstractAsset
{
    /**
     * @var bool
     */
    private $inFooter = true;

    /**
     * @return bool
     */
    public function getInFooter()
    {
        return $this->inFooter;
    }

    /**
     * @param bool $inFooter
     * @return Script
     */
    public function setInFooter($inFooter)
    {
        $this->inFooter = $inFooter;

        return $this;
    }

    /**
     * Execute function wp_enqueue_script with properties
     */
    public function enqueue()
    {
        wp_enqueue_script($this->getHandle(), $this->getSrc(), $this->getDeps(), $this->getVer(), $this->getInFooter());
    }
}