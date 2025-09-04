<?php

namespace Core;

class Logger
{
    private const LOG_FILE = __DIR__ . '/../../storage/logs/app.log';

    public static function log(string $message): void
    {
        $dir = dirname(self::LOG_FILE);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $date = date('Y-m-d H:i:s');
        $formatted = "[$date] $message\n";
        file_put_contents(self::LOG_FILE, $formatted, FILE_APPEND);
    }
}
