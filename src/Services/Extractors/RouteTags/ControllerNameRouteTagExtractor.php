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
     * @param OpenApiRouteExtractionContext $_context
     * @param RouteService $_routeService
     */
    public function __construct(
        private OpenApiRouteExtractionContext $_context,
        private RouteService                  $_routeService
    ) {
    }

    /**
     * @inheritDoc
     */
    public function extract(): Collection
    {
        if ($this->_context->route) {
            $controller = $this->_routeService->getControllerForRoute($this->_context->route->routeDefinition);
            if ($controller) {
                return new Collection([$controller]);
            }
        }
        return new Collection();
    }
}
