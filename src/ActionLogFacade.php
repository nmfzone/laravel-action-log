<?php

namespace NMFCODES\ActionLog;

use Illuminate\Support\Facades\Facade;

class ActionLogFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ActionLog::class;
    }
}
