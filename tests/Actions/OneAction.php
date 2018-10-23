<?php

namespace NMFCODES\ActionLog\Tests\Actions;

use NMFCODES\ActionLog\Contracts\Action;

class OneAction implements Action
{
    public function handle()
    {
        echo 'One Action Executed!';
    }

    public function reverse()
    {
        echo 'One Action Reversed!';
    }
}
