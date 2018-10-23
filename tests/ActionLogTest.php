<?php

namespace NMFCODES\ActionLog\Tests;

use NMFCODES\ActionLog\ActionLogFacade;
use NMFCODES\ActionLog\Tests\Actions\OneAction;
use NMFCODES\ActionLog\Tests\Actions\TwoAction;
use NMFCODES\ActionLog\Tests\Actions\ThreeAction;

class ActionLogTest extends TestCase
{
    /** @test */
    public function test_nothing_is_passed()
    {
        ActionLogFacade::defineListeners([
            OneAction::class => [
                TwoAction::class,
                ThreeAction::class,
            ],
            TwoAction::class => [
                ThreeAction::class,
            ],
        ]);

        $model = ActionLogFacade::run(new OneAction());
        $model = $model->fresh();
        $this->assertEquals('OneAction', $model->action_name);
        $this->assertFalse($model->is_reversed);

        ActionLogFacade::reverse($model);
        $this->assertTrue($model->fresh()->is_reversed);

        $model = ActionLogFacade::run(new TwoAction());
        $model = $model->fresh();
        $this->assertEquals('TwoAction', $model->action_name);
        $this->assertFalse($model->is_reversed);

        ActionLogFacade::reverse($model->uuid);
        $this->assertTrue($model->fresh()->is_reversed);
    }
}
