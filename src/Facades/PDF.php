<?php

namespace Breuer\MakePDF\Facades;

use Illuminate\Support\Facades\Facade;

/**
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
