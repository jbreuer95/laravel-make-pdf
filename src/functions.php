<?php

namespace Breuer\PDF;

use Illuminate\Support\Facades\App;

if (! function_exists('Breuer\PDF\package_path')) {
    function package_path(string $path = ''): string
    {
        return App::joinPaths(dirname(__FILE__, 2), $path);
    }
}
