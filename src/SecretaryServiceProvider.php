<?php

namespace CodyMoorhouse\Secretary;

use Illuminate\Support\ServiceProvider;

/* Command */
use CodyMoorhouse\Secretary\Commands\SectionCommand;

class SecretaryServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/secretary.php', 'secretary');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
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
            __DIR__.'/config/secretary.php' => config_path('secretary.php'),
        ]);
        $this->publishes([
            __DIR__.'/assets/compiled/app.js' => public_path('vendor/secretary.js'),
        ], 'public');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SectionCommand::class
            ]);
        }
    }
}

?>
