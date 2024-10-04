<?php

namespace Breuer\MakePDF\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\Response response()
 * @method static \Illuminate\Http\Response download()
 * @method static \Breuer\MakePDF\Client view(string $view, array $data = [])
 * @method static \Breuer\MakePDF\Client headerView(string $view, array $data = [])
 * @method static \Breuer\MakePDF\Client footerView(string $view, array $data = [])
 * @method static \Breuer\MakePDF\Client html(string $html)
 * @method static \Breuer\MakePDF\Client headerHtml(string $html)
 * @method static \Breuer\MakePDF\Client footerHtml(string $html)
 * @method static \Breuer\MakePDF\Client name(string $filename)
 * @method static string chromeDriverBinary()
 * @method static string chromeHeadlessBinary()
 * @method static bool onWindows32()
 * @method static bool onWindows64()
 * @method static bool onMacARM()
 * @method static bool onMacIntel()
 * @method static bool onLinux()
 *
 * @see \Breuer\MakePDF\Client
 */
class PDF extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Breuer\MakePDF\Client::class;
    }
}
