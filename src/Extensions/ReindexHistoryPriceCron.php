<?php

namespace PerfectWPWCO\Extensions;

use PerfectWPWCO\Plugin;
use PerfectWPWCO\Tools\BulkReindex;

class ReindexHistoryPriceCron
{
    public function boot()
    {
        add_action(self::getActionName(), [$this, 'executeCronJob'], 10, 1);
    }

    public static function getActionName(): string
    {
        return Plugin::SLUG . '_bulk_reindex_history_prices';
    }

    public static function dispatch(int $cursorId = 0, int $delay = 2)
    {
        wp_schedule_single_event(time() + $delay, ReindexHistoryPriceCron::getActionName(), [$cursorId]);
    }

    public function executeCronJob($cursorId)
    {
        $bulkReindex = new BulkReindex();
        $bulkReindex->reindex($cursorId);
    }
}