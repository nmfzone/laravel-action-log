<?php

namespace NMFCODES\ActionLog\Tests;

use CreateActionLogTable;
use NMFCODES\ActionLog\ActionLogServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            ActionLogServiceProvider::class,
        ];
    }

    protected function setUpDatabase()
    {
        include_once __DIR__.'/../migrations/create_action_logs_table.php';

        (new CreateActionLogTable())->up();
    }
}
