<?php

namespace NMFCODES\ActionLog\Contracts;

interface Action
{
    /**
     * Run the action.
     *
     * @return void
     */
    public function handle();

    /**
     * Reverse the action.
     *
     * @return void
     */
    public function reverse();
}
