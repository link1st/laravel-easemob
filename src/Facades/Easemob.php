<?php namespace link1st\Easemob\Facades;

use Illuminate\Support\Facades\Facade;

class Easemob extends Facade
{

    /**
     *
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Easemob';
    }
}
