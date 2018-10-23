<?php

namespace NMFCODES\ActionLog\Tests\Actions;

use NMFCODES\ActionLog\Contracts\Action;

class TwoAction implements Action
{
    public function handle()
    {
        echo 'Two Action Executed!';
    }

    public function reverse()
    {
        echo 'Two Action Reversed!';
    }
}
