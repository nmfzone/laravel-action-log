<?php

namespace NMFCODES\ActionLog\Tests\Actions;

use NMFCODES\ActionLog\Contracts\Action;

class ThreeAction implements Action
{
    public function handle()
    {
        echo 'Three Action Executed!';
    }

    public function reverse()
    {
        echo 'Three Action Reversed!';
    }
}
