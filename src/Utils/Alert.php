<?php

namespace PerfectWPWCO\Utils;

if (!defined('ABSPATH')) exit;

class Alert
{
    public static function flush()
    {
        foreach (self::getMessages() as $alert) {
        ?>
            <div id="message" class="notice notice-<?php echo $alert['level']; ?> is-dismissible">
                <p><?php echo $alert['message']; ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Hide.', 'pwp-wco'); ?></span></button>
            </div>
        <?php
        }

        self::reset();
    }

    public static function success($message)
    {
        self::add('success', $message);
    }

    public static function error($message)
    {
        self::add('error', $message);
    }

    private static function getMessages()
    {
        if (!session_id()) {
            session_start();
        }

        return isset($_SESSION['wppd_plugin_alerts']) ? $_SESSION['wppd_plugin_alerts'] : [];
    }

    private static function add($level, $message)
    {
        if (!session_id()) {
            session_start();
        }

        if (!isset($_SESSION['wppd_plugin_alerts'])) {
            $_SESSION['wppd_plugin_alerts'] = [];
        }

        $_SESSION['wppd_plugin_alerts'][] = [
            'message' => $message,
            'level' => $level
        ];
    }

    private static function reset()
    {
        $_SESSION['wppd_plugin_alerts'] = [];
    }
}