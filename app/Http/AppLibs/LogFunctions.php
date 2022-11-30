<?php

namespace App\Http\AppLibs;

use App\Models\t022_log as Log;

class LogFunctions
{
    /**
     * 通常ログ
     */
    public static function info($window_id, $operation_id, $log_text, $operation_target)
    {
        $log = new Log();
        $log->insertLog($window_id, $operation_id, $log_text, $operation_target, 1);
    }
    /**
     * 警告ログ
     */
    public static function warn($window_id, $operation_id, $log_text, $operation_target)
    {
        $log = new Log();
        $log->insertLog($window_id, $operation_id, $log_text, $operation_target, 2);
    }
    /**
     * エラーログ
     */
    public static function error($window_id, $operation_id, $log_text, $operation_target)
    {
        $log = new Log();
        $log->insertLog($window_id, $operation_id, $log_text, $operation_target, 3);
    }
}