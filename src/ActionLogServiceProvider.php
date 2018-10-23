<?php

namespace NMFCODES\ActionLog;

use Illuminate\Support\ServiceProvider;

class ActionLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/action-log.php' => config_path('action-log.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../config/action-log.php', 'action-log');

        if (! class_exists('CreateActionLogTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../migrations/create_action_logs_table.php' => database_path("/migrations/{$timestamp}_create_action_logs_table.php"),
            ], 'migrations');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ActionLog::class, function ($app) {
            return new ActionLog($app);
        });
    }
}
