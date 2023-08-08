<?php

namespace IrealWorlds\OpenApi\Services;

use Closure;
use Illuminate\Routing\{Route, Router};
use IrealWorlds\OpenApi\Models\{OpenApiRouteExtractionContext, RegisteredRouteDto};
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;

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
    public function getRegisteredRoutes(): array
    {
        $registeredRoutes = [];

        $routes = $this->_router->getRoutes();
        foreach ($routes->getRoutes() as $route) {

            foreach ($route->methods() as $method) {
                $registeredRoutes[] = new RegisteredRouteDto(
                    $route->uri(),
                    $method,
                    $route
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
    public function getControllerForRoute(Route $route): string|null {
        $action = $route->getAction();

        if ($controller = $route->getAction('controller')) {
            $controller = explode('@', $controller)[0];
            return last(explode('\\', $controller));
        } else if (isset($action[0]) && is_string($action[0])) {
            $controller = $action[0];
            return last(explode('\\', $controller));
        }

        return null;
    }

    /**
     * Get a reflection to the callable action this route calls.
     *
     * @param Route $route
     * @return ReflectionMethod|ReflectionFunction|null
     * @throws ReflectionException
     */
    protected function getRouteCallableReflection(Route $route): ReflectionMethod|ReflectionFunction|null
    {
        if ($controller = $route->getAction('controller')) {
            [$controller, $action] = explode('@', $controller);
            $classReflection = new ReflectionClass($controller);
            return $classReflection->getMethod($action);
        } else if ($action = $route->getAction('uses')) {
            if ($action instanceof Closure) {
                return new ReflectionFunction($action);
            }
        } else if (($action = $route->getAction()) && isset($action[0])) {
            $controller = $action[0];
            if (class_exists($controller) && method_exists($controller, "__invoke")) {
                $classReflection = new ReflectionClass($controller);
                return $classReflection->getMethod("__invoke");
            }
        }

        return null;
    }

    /**
     * Get the extractor context for a given route.
     *
     * @param Route $route
     * @return OpenApiRouteExtractionContext
     * @throws ReflectionException
     */
    public function buildExtractorContextForRoute(RegisteredRouteDto $route): OpenApiRouteExtractionContext
    {
        return new OpenApiRouteExtractionContext(
            route: $route,
            action: $this->getRouteCallableReflection($route->routeDefinition)
        );
    }
}
