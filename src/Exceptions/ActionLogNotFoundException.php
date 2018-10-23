<?php

namespace NMFCODES\ActionLog\Exceptions;

use Exception;

class ActionLogNotFoundException extends Exception
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
        parent::__construct('Action Log with the given criteria not found!', $code, $previous);
    }
}
