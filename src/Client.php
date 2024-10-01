<?php

namespace Breuer\PDF;

class Client
{
    public function html(string $text): string
    {
        return $text;
    }

    public static function onWindows(): bool
    {
        return PHP_OS_FAMILY === 'Windows';
    }

    public static function onMac(): bool
    {
        return PHP_OS_FAMILY === 'Darwin';
    }

    public static function onLinux(): bool
    {
        return PHP_OS_FAMILY === 'Linux';
    }
}
