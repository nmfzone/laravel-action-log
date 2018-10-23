<?php

namespace NMFCODES\ActionLog\Exceptions;

use Exception;

class ActionFailedException extends Exception
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
        parent::__construct('Failed to execute the Action!', $code, $previous);
    }
}
