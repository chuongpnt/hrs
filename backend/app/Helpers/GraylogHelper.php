<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class GraylogHelper
{
    protected static function serviceName()
    {
        return 'laravel_app';
    }

    public static function info($message, $context = [])
    {
        Log::channel('graylog')->info($message, self::withCommonFields($context));
    }

    public static function error($message, $context = [])
    {
        Log::channel('graylog')->error($message, self::withCommonFields($context));
    }

    public static function warning($message, $context = [])
    {
        Log::channel('graylog')->warning($message, self::withCommonFields($context));
    }

    protected static function withCommonFields($context)
    {
        // Add whatever field you want to every log, eg: user id, IP, etc
        return array_merge($context, [
            'service' => self::serviceName(),
            'user_id' => auth()->id() ?? null,
            'ip' => request()->ip() ?? null,
        ]);
    }
}
