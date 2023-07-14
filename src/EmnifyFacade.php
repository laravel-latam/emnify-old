<?php

namespace LaravelLatam\Emnify;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LaravelLatam\Emnify\Emnify
 */
class EmnifyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'emnify';
    }
}
