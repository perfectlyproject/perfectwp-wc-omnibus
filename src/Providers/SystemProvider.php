<?php

namespace PerfectWPWCO\Providers;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Plugin;
use PerfectWPWCO\System\Installer;
use PerfectWPWCO\System\Migrations;
use PerfectWPWCO\System\System;
use PerfectWPWCO\System\Uninstaller;

class SystemProvider
{
    public function boot()
    {
        $this->registerInstaller();
        $this->registerUninstaller();
        $this->registerMigrations();
    }

    public function registerInstaller()
    {
        $installer = new Installer();
        register_activation_hook(Plugin::getInstance()->getPluginPath(), [$installer, 'install']);
    }

    public function registerUninstaller()
    {
        register_uninstall_hook(Plugin::getInstance()->getPluginPath(), [Uninstaller::class, 'uninstall']);
    }

    public function registerMigrations()
    {
        add_action('init', function() {
            if (is_admin()) {
                $migrations = new Migrations();
                $migrations->migrate(System::getDBVersion());
            }
        });

        add_action('upgrader_process_complete', function() {
            $migrations = new Migrations();
            $migrations->migrate(System::getDBVersion());
        });
    }
}