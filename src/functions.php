<?php

namespace Breuer\MakePDF;

use function Illuminate\Filesystem\join_paths;

if (! function_exists('Breuer\PDF\package_path')) {
    function package_path(string $path = '', string ...$paths): string
    {
        return join_paths(dirname(__FILE__, 2), $path, ...$paths);
    }
}
