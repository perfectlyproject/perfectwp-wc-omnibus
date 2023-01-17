<?php

namespace PerfectWPWCO\Extensions;

use PerfectWPWCO\Plugin;
use PerfectWPWCO\Tools\BulkReindex;

class ReindexHistoryPriceCron
{
    public function boot()
    {
        add_action(self::getActionName(), [$this, 'executeCronJob']);
    }

    public static function getActionName(): string
    {
        return Plugin::SLUG . '_bulk_reindex_history_prices';
    }

    public function executeCronJob()
    {
        $bulkReindex = new BulkReindex();
        $bulkReindex->reindex();
    }
}