<?php

namespace Breuer\PDF;

use Illuminate\Support\Facades\App;

if (! function_exists('Breuer\PDF\package_path')) {
    /**
     * Join the given paths together.
     *
     * @param  string|null  $basePath
     * @param  string  ...$paths
     * @return string
     */
    function package_path($path = '')
    {
        return App::joinPaths(dirname(__FILE__, 2), $path);
    }
}
