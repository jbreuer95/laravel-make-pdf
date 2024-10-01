<?php

namespace Breuer\PDF;

class Client
{
    public function html($text): string
    {
        return $text;
    }

    public static function onWindows()
    {
        return PHP_OS_FAMILY === 'Windows';
    }

    public static function onMac()
    {
        return PHP_OS_FAMILY === 'Darwin';
    }

    public static function onLinux()
    {
        return PHP_OS_FAMILY === 'Linux';
    }
}
