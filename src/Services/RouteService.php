<?php

namespace IrealWorlds\OpenApi\Services;

use Illuminate\Http\Request;
use IrealWorlds\OpenApi\Models\RouteParameterDto;
use Illuminate\Routing\{Route, RouteRegistrar, Router};
use IrealWorlds\OpenApi\Models\RegisteredRouteDto;

readonly class RouteService
{
    public function __construct(
        private Router $_router
    ) {
    }

    /**
     * Get the routes currently known to the route registrar.
     *
     * @return array<RegisteredRouteDto>
     */
    public function getRegisteredRoutes(): array {
        $registeredRoutes = [];

        $routes = $this->_router->getRoutes();
        foreach ($routes->getRoutes() as $route) {

            foreach ($route->methods() as $method) {
                $tags = [];

                // If a controller can be identified, add it as a tag
                if ($controller = $this->getControllerForRoute($route)) {
                    $tags[] = $controller;
                }

                // Extract route parameters
                $parameters = $this->getRouteParameters($route);

                $registeredRoutes[] = new RegisteredRouteDto(
                    $route->uri(),
                    $method,
                    $tags,
                    $parameters
                );
            }
        }

        return $registeredRoutes;
    }

    /**
     * Get the name of the controller pointed to by a route.
     *
     * @param Route $route
     * @return string|null
     */
    protected function getControllerForRoute(Route $route): string|null {
        $action = $route->getAction();

        if ($controller = $route->getAction('controller')) {
            $controller = explode('@', $controller)[0];
            return last(explode('\\', $controller));
        } else if (isset($action[0])) {
            if (is_string($action[0])) {
                $controller = $action[0];
                return last(explode('\\', $controller));
            }
        }

        return null;
    }

    /**
     * Get the parameters defined in a given route.
     *
     * @param Route $route
     * @return array<RouteParameterDto>
     */
    protected function getRouteParameters(Route $route): array {
        // TODO find a way to do this without regex
        $parameters = [];
        $uri = $route->uri();
        preg_match_all('/\/{([a-zA-Z_]+)(\??)}/', $uri, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $parameterName = $match[1];
            $isOptional = isset($match[2]) && $match[2] === '?';

            $parameters[] = new RouteParameterDto($parameterName, !$isOptional);
        }

        return $parameters;
    }
}