<?php

namespace IrealWorlds\OpenApi\Providers;

use Illuminate\Support\ServiceProvider;
use IrealWorlds\OpenApi\Contracts\IExtractorRegistrar;
use IrealWorlds\OpenApi\Services\ExtractorRegistrar;
use IrealWorlds\OpenApi\Services\Extractors\{RouteParameters\ReflectionRoutePathParametersExtractor,
    RouteSummary\DocBlockRouteSummaryExtractor,
    RouteTags\ControllerNameRouteTagExtractor};

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

        // Register the extractor registrar
        $this->app->singleton(IExtractorRegistrar::class, ExtractorRegistrar::class);

        // Register default extractors
        /** @var IExtractorRegistrar $registrar */
        $registrar = $this->app->make(IExtractorRegistrar::class);
        $registrar->registerRouteTagsExtractor(ControllerNameRouteTagExtractor::class);
        $registrar->registerRouteSummaryExtractor(DocBlockRouteSummaryExtractor::class);
        $registrar->registerRouteParametersExtractor(ReflectionRoutePathParametersExtractor::class);
    }
}
