<?php

namespace Zaazy\Foundation\Facades;

use Illuminate\Support\Facades\Facade;

class Foundation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'zaaz.foundation';
    }
}
