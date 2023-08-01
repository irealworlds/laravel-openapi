<?php

namespace IrealWorlds\OpenApi\Models;

readonly class RegisteredRouteDto
{
    /**
     * A route that has been registered in the route registrar.
     *
     * @param string $uri
     * @param string $method
     * @param array<string> $tags
     * @param array<RouteParameterDto> $parameters
     */
    public function __construct(
        public string $uri,
        public string $method,
        public array $tags = [],
        public array $parameters = [],
    ) {
    }
}