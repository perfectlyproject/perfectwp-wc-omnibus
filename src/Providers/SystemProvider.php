<?php

namespace PerfectWPWCO\Providers;

if (!defined('ABSPATH')) exit;

use PerfectWPWCO\Plugin;
use PerfectWPWCO\System\Installer;
use PerfectWPWCO\System\Uninstaller;

class SystemProvider
{
    public function boot()
    {
        $this->registerInstaller();
        $this->registerUninstaller();
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
}