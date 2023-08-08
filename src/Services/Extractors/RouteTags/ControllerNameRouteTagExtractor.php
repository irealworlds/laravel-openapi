<?php

namespace IrealWorlds\OpenApi\Services\Extractors\RouteTags;

use Illuminate\Support\Collection;
use IrealWorlds\OpenApi\Contracts\Extractors\IRouteTagExtractor;
use IrealWorlds\OpenApi\Models\OpenApiRouteExtractionContext;
use IrealWorlds\OpenApi\Services\RouteService;

/**
 * Extract the controller's name as a tag.
 */
readonly class ControllerNameRouteTagExtractor implements IRouteTagExtractor
{
    /**
     * ControllerNameRouteTagExtractor constructor method.
     *
     * @param RouteService $_routeService
     */
    public function __construct(
        private RouteService                  $_routeService
    ) {
    }

    /**
     * @inheritDoc
     */
    public function extract(OpenApiRouteExtractionContext $context): Collection
    {
        if ($context->route) {
            $controller = $this->_routeService->getControllerForRoute($context->route->routeDefinition);
            if ($controller) {
                return new Collection([$controller]);
            }
        }
        return new Collection();
    }
}
