<?php

namespace PerfectWPWCO\Contracts;
interface Shortcode
{
    /**
     * Return tag for shortcode
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Render shortcode content
     *
     * @param $attributes
     * @return string
     */
    public function render($attributes): string;
}