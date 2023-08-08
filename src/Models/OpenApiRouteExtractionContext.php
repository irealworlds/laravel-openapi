<?php

namespace IrealWorlds\OpenApi\Models;

use Illuminate\Routing\Route;
use ReflectionFunction;
use ReflectionMethod;

readonly class OpenApiRouteExtractionContext
{
    public function __construct(
        public RegisteredRouteDto|null $route = null,
        public ReflectionMethod|ReflectionFunction|null $action = null
    ) {
    }
}
