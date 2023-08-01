<?php

namespace IrealWorlds\OpenApi\Services;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteRegistrar;
use IrealWorlds\OpenApi\Models\Document\Paths\PathEndpointDto;
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

                $registeredRoutes[] = new RegisteredRouteDto(
                    $route->uri(),
                    $method,
                    $tags
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
}