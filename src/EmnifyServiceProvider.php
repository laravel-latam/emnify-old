<?php

namespace LaravelLatam\Emnify;

use LaravelLatam\Emnify\View\Components\Layout;
use LaravelLatam\Emnify\View\Components\MenuUser;
use LaravelLatam\Emnify\Commands\EmnifyCommand;
use LaravelLatam\Emnify\Http\Middleware\VerifyRedirectUrl;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class EmnifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();
        $this->registerResources();
        $this->registerMigrations();
        $this->registerPublishing();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/emnify.php' => config_path('emnify.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/emnify'),
            ], 'views');

            $migrationFileName = 'create-emnify_table.php';
            if (!$this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            $this->commands([
                EmnifyCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/emnify.php', 'emnify');
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        if (Emnify::$registersRoutes) {
            Route::group([
                'middleware' => ['web', VerifyRedirectUrl::class, 'auth:sanctum', 'verified'],
                'prefix' => config('emnify.path'),
                'namespace' => 'LaravelLatam\Emnify\Http\Controllers',
                'as' => 'emnify.',
            ], function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
            });
        }
    }

    /**
     * Register the package resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'emnify');
        $this->loadViewComponentsAs('emnify', [
            Layout::class,
            MenuUser::class,
        ]);
    }

    /**
     * Register the package migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (Emnify::$runsMigrations && $this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/emnify.php' => $this->app->configPath('emnify.php'),
            ], 'emnify-config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'emnify-migrations');

            $this->publishes([
                __DIR__ . '/../resources/views' => $this->app->resourcePath('views/vendor/emnify'),
            ], 'emnify-views');
        }
    }
    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
