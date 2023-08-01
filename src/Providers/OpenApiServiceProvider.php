<?php

namespace IrealWorlds\OpenApi\Providers;

use Illuminate\Support\ServiceProvider;

class OpenApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/../../config/openapi.php' => $this->app->configPath('openapi.php'),
        ]);

        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/openapi.php',
            'openapi'
        );
    }
}