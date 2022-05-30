<?php

namespace PerfectWPWCO\Assets;

if (!defined('ABSPATH')) exit;

class Style extends AbstractAsset
{
    /**
     * @var string
     */
    private $media = 'all';

    /**
     * @return string
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param string $media
     * @return Style
     */
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    public function enqueue()
    {
        wp_enqueue_style($this->getHandle(), $this->getSrc(), $this->getDeps(), $this->getVer(), $this->getMedia());
    }
}