<?php

namespace Breuer\PDF\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Breuer\PDF\PDF
 */
class PDF extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Breuer\PDF\Client::class;
    }
}
