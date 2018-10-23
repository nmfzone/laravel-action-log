<?php

namespace NMFCODES\ActionLog;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use NMFCODES\ActionLog\Contracts\Action;
use Illuminate\Contracts\Container\Container;
use NMFCODES\ActionLog\Exceptions\ActionFailedException;
use NMFCODES\ActionLog\Exceptions\ActionNotFoundException;
use NMFCODES\ActionLog\Models\ActionLog as ActionLogModel;
use NMFCODES\ActionLog\Exceptions\ActionLogNotFoundException;
use NMFCODES\ActionLog\Exceptions\ReverseActionFailedException;

class ActionLog
{
    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The registered action listeners.
     *
     * @var array
     */
    protected $listeners = [];

    /**
     * Create a new Action Log instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Run the given action.
     *
     * @param  \NMFCODES\ActionLog\Contracts\Action  $action
     * @return \NMFCODES\ActionLog\Models\ActionLog
     *
     * @throws \Exception
     */
    public function run(Action $action)
    {
        return DB::transaction(function () use ($action) {
            $this->executeAction($action);

            return ActionLogModel::forceCreate([
                'uuid' => Str::uuid(),
                'action_name' => Str::limit(Str::studly(class_basename($action)), 250),
                'action_class' => get_class($action),
                'user_id' => Auth::check() ? Auth::user()->id : null,
            ]);
        });
    }

    /**
     * Execute reverse action for the given action log.
     *
     * @param  \NMFCODES\ActionLog\Models\ActionLog|string  $actionLog
     * @return \NMFCODES\ActionLog\Models\ActionLog
     *
     * @throws \Exception
     * @throws \NMFCODES\ActionLog\Exceptions\ActionLogNotFoundException
     * @throws \NMFCODES\ActionLog\Exceptions\ActionNotFoundException
     */
    public function reverse($actionLog)
    {
        if (is_string($actionLog)) {
            try {
                $actionLog = ActionLogModel::whereUuid($actionLog)->firstOrFail();
            } catch (Exception $e) {
                throw new ActionLogNotFoundException($e);
            }
        }

        $action = $this->makeAction($actionLog->action_class);

        return DB::transaction(function () use ($actionLog, $action) {
            $this->executeReverseAction($action);

            return tap($actionLog->forceFill([
                'is_reversed' => true,
            ]))->save();
        });
    }

    /**
     * Execute the given action.
     *
     * @param  \NMFCODES\ActionLog\Contracts\Action  $action
     * @return void
     *
     * @throws \NMFCODES\ActionLog\Exceptions\ActionFailedException
     */
    protected function executeAction(Action $action)
    {
        try {
            DB::transaction(function () use ($action) {
                $action->handle();

                foreach ((array) Arr::get($this->listeners, get_class($action)) as $listener) {
                    $listener->handle();
                }
            });
        } catch (Exception $e) {
            throw new ActionFailedException($e);
        }
    }

    /**
     * Reverse the given action.
     *
     * @param  \NMFCODES\ActionLog\Contracts\Action  $action
     * @return void
     *
     * @throws \NMFCODES\ActionLog\Exceptions\ReverseActionFailedException
     */
    protected function executeReverseAction(Action $action)
    {
        try {
            $listeners = (array) Arr::get($this->listeners, get_class($action));
            $listeners = array_reverse($listeners);

            foreach ($listeners as $listener) {
                $listener->reverse();
            }

            $action->reverse();
        } catch (Exception $e) {
            throw new ReverseActionFailedException($e);
        }
    }

    /**
     * Register listener to the given action.
     *
     * @param  array  $actions
     * @return void
     *
     * @throws \NMFCODES\ActionLog\Exceptions\ActionNotFoundException
     */
    public function defineListeners(array $actions)
    {
        foreach ($actions as $action => $listeners) {
            foreach ((array) $listeners as $listener) {
                $this->listen($action, $listener);
            }
        }
    }

    /**
     * Register listener to the given action.
     *
     * @param  string  $action
     * @param  string  $listener
     * @return void
     *
     * @throws \NMFCODES\ActionLog\Exceptions\ActionNotFoundException
     */
    protected function listen(string $action, string $listener)
    {
        $this->listeners[$action][] = $this->makeAction($listener);
    }

    /**
     * Make the given action.
     *
     * @param  string  $action
     * @return \NMFCODES\ActionLog\Contracts\Action
     *
     * @throws \NMFCODES\ActionLog\Exceptions\ActionNotFoundException
     */
    protected function makeAction(string $action)
    {
        try {
            return $this->container->make($action);
        } catch (Exception $e) {
            throw new ActionNotFoundException($e);
        }
    }
}
