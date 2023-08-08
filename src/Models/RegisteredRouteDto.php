<?php

namespace IrealWorlds\OpenApi\Models;

use Illuminate\Routing\Route;
use ReflectionFunction;
use ReflectionMethod;

readonly class RegisteredRouteDto
{
    /**
     * A route that has been registered in the route registrar.
     *
     * @param string $uri
     * @param string $method
     * @param Route $routeDefinition
     */
    public function __construct(
        public string $uri,
        public string $method,
        public Route $routeDefinition
    ) {
    }
}
