<?php

if (!defined('ABSPATH')) exit;

if (! function_exists('pwp_wco_value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function pwp_wco_value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}