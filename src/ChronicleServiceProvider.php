<?php

namespace Pderas\Chronicle;

use Illuminate\Support\ServiceProvider;

/* Command */
use Pderas\Chronicle\Commands\SectionCommand;

class ChronicleServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/chronicle.php', 'chronicle');
        $this->loadRoutesFrom(__DIR__ .'/routes.php');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/chronicle.php' => config_path('chronicle.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/assets/js/components' => base_path('resources/assets/js/components/chronicle'),
        ], 'chronicle-components');

        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations')
        ], 'migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SectionCommand::class
            ]);
        }
    }
}

?>
