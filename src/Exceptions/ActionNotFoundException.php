<?php

namespace NMFCODES\ActionLog\Exceptions;

use Exception;

class ActionNotFoundException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  \Exception  $previous
     * @param  int  $code
     * @return void
     */
    public function __construct(Exception $previous = null, $code = 0)
    {
        parent::__construct('Action not found!', $code, $previous);
    }
}
