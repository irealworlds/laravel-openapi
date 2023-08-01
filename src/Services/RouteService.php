<?php

namespace IrealWorlds\OpenApi\Services;

use Closure;
use ReflectionMethod;
use Illuminate\Routing\{Route, Router};
use IrealWorlds\OpenApi\Models\{RegisteredRouteDto, RouteParameterDto};
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
                $routeParameters = array_map(function (ReflectionParameter $parameter) use ($route) {
                    $parameterDto = new RouteParameterDto(
                        $parameter->getName(),
                        $parameter->getType()
                    );

                    if (isset($route->wheres[$parameter->getName()])) {
                        $parameterDto->pattern = $route->wheres[$parameter->getName()];
                    }

                    if ($parameter->isOptional()) {
                        $parameterDto->defaultValue = $parameter->getDefaultValue();
                    }

                    return $parameterDto;
                }, $routeParameters);

                $registeredRoutes[] = new RegisteredRouteDto(
                    $route->uri(),
                    $method,
                    summary: $this->getRouteSummary($route),
                    tags: $tags,
                    parameters: $routeParameters
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
        } else if ($action = $route->getAction()) {
            if (isset($action[0])) {
                $controller = $action[0];
                if (class_exists($controller)) {
                    if (method_exists($controller, "__invoke")) {
                        $classReflection = new ReflectionClass($controller);
                        return $classReflection->getMethod("__invoke");
                    }
                }
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
    protected function getActionParameters(Route $route): array
    {
        if ($reflection = $this->getRouteCallableReflection($route)) {
            return $reflection->getParameters();
        }

        return [];
    }

    /**
     * Get the summary for the given route.
     *
     * @param Route $route
     * @return string|null
     * @throws ReflectionException
     */
    protected function getRouteSummary(Route $route): ?string
    {
        if ($reflection = $this->getRouteCallableReflection($route)) {
            $comment = $reflection->getDocComment();

            if ($comment !== false) {

                // Remove comment delimiters (/* and */)
                $comment = substr($comment, 3, -2);

                // Remove leading and trailing whitespace
                $comment = trim($comment);

                // Remove leading asterisks and additional spaces
                $comment = preg_replace('/^\s*\*+\s?/m', '', $comment);

                // Extract the summary from the doc block
                if (preg_match('/^([^\n]+)/', $comment, $matches)) {
                    $summary = $matches[1];
                } else {
                    // If no explicit summary found, use the first non-empty line as the summary
                    $lines = array_filter(explode("\n", $comment), 'trim');
                    $summary = trim($lines[0] ?? '');
                }

                return $summary;
            }
        }

        return null;
    }
}