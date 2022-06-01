<?php
/**
 * Plugin Name: PerfectWP - WC Omnibus
 * Plugin URI: https://perfectlyproject.pl/
 * Description: Compatibility with EU Omnibus Directive
 * Author: PerfectWP
 * Version: {VERSION}
 * Requires at least: 5.4
 * WC requires at least: 4.0
 * Requires PHP: 7.0
 * Text Domain: perfectwp-wc-omnibus
 * Domain Path: /languages
 *
 * Copyright 2022 PerfectWP
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @package PerfectWPWCO
 */

if (!defined('ABSPATH')) exit;

/**
 * Load composer vendors
 */
if (!file_exists($composer = __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php')) {
    die('We could not find the composer autoload, You have to run in the command line "composer install"');
} else {
    require_once $composer;
}

\PerfectWPWCO\Plugin::load(__FILE__);