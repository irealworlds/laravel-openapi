<?php

namespace IrealWorlds\OpenApi\Services;

use Closure;
use Illuminate\Routing\{Route, Router};
use IrealWorlds\OpenApi\Models\{RegisteredRouteDto};
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionParameter;

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
     * @throws ReflectionException
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

                // Extract parameters defined in the action
                $parameters = $this->getActionParameters($route);

                // Extract only parameters that are defined in the route
                $pattern = '/\/{([a-zA-Z_]+)(\??)}/';
                $matches = [];
                preg_match_all($pattern, $route->uri(), $matches);
                $routeParameters = array_filter(
                    $parameters,
                    fn(ReflectionParameter $parameter) => in_array($parameter->getName(), $matches[1])
                );

                $registeredRoutes[] = new RegisteredRouteDto(
                    $route->uri(),
                    $method,
                    $tags,
                    $routeParameters
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
     * @return array<ReflectionParameter'>
     * @throws ReflectionException
     */
    protected function getActionParameters(Route $route): array {

        if ($controller = $route->getAction('controller')) {
            [$controller, $action] = explode('@', $controller);
            $classReflection = new ReflectionClass($controller);
            $methodReflection = $classReflection->getMethod($action);
            return $methodReflection->getParameters();
        } else if ($action = $route->getAction('uses')) {
            if ($action instanceof Closure) {
                $methodReflection = new ReflectionFunction($action);
                return $methodReflection->getParameters();
            }
        } else if ($action = $route->getAction()) {
            if (isset($action[0])) {
                $controller = $action[0];
                if (class_exists($controller)) {
                    if (method_exists($controller, "__invoke")) {
                        $classReflection = new ReflectionClass($controller);
                        $methodReflection = $classReflection->getMethod("__invoke");
                        return $methodReflection->getParameters();
                    }
                }
            }
        }

        return [];
    }
}