<?php

namespace IrealWorlds\OpenApi\Services\Extractors\RouteParameters;

use Illuminate\Support\Collection;
use IrealWorlds\OpenApi\Contracts\Extractors\IRouteParametersExtractor;
use IrealWorlds\OpenApi\Enums\RouteParameterLocation;
use IrealWorlds\OpenApi\Models\Document\Paths\EndpointParameterDto;
use IrealWorlds\OpenApi\Models\OpenApiRouteExtractionContext;
use IrealWorlds\OpenApi\Services\SchemaService;
use ReflectionParameter;

/**
 * Extract the parameters that should be present in the path, using reflection.
 */
class ReflectionRoutePathParametersExtractor implements IRouteParametersExtractor
{
    private readonly OpenApiRouteExtractionContext $_context;
    private readonly SchemaService $_schemaService;

    public function __construct(
        OpenApiRouteExtractionContext $context,
        SchemaService $schemaService
    ) {
        $this->_schemaService = $schemaService;
        $this->_context = $context;
    }

    /**
     * @inheritDoc
     */
    public function extract(): Collection
    {
        if ($reflection = $this->_context->action) {
            // Extract parameters defined in the action
            $parameters = $reflection->getParameters();

            // Extract only parameters that are defined in the route
            $pattern = '/\/{([a-zA-Z_]+)(\??)}/';
            $matches = [];
            preg_match_all($pattern, $this->_context->route->uri, $matches);
            return (new Collection($parameters))
                ->filter(fn(ReflectionParameter $parameter) => in_array($parameter->getName(), $matches[1]))
                ->map(function (ReflectionParameter $parameter) {
                    $parameterDto = new EndpointParameterDto(
                        in: RouteParameterLocation::Path,
                        name: $parameter->getName(),
                    );

                    $parameterDto->required = !$parameter->getType()->allowsNull();
                    $parameterDto->schema = $this->_schemaService->createFromType($parameter->getType());

                    if (isset($this->_context->route->routeDefinition->wheres[$parameter->getName()])) {
                        $parameterDto->schema->pattern = $this->_context->route
                            ->routeDefinition
                            ->wheres[$parameter->getName()];
                    }

                    if ($parameter->isOptional()) {
                        $parameterDto->schema->default = $parameter->getDefaultValue();
                    }

                    return $parameterDto;
                });
        }
        return new Collection();
    }
}
