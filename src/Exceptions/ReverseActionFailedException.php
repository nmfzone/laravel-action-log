<?php

namespace NMFCODES\ActionLog\Exceptions;

use Exception;

class ReverseActionFailedException extends Exception
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
        parent::__construct('Failed to reverse the Action!', $code, $previous);
    }
}
