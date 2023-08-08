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
readonly class ReflectionRoutePathParametersExtractor implements IRouteParametersExtractor
{
    private SchemaService $_schemaService;

    public function __construct(
        SchemaService $schemaService
    ) {
        $this->_schemaService = $schemaService;
    }

    /**
     * @inheritDoc
     */
    public function extract(OpenApiRouteExtractionContext $context): Collection
    {
        if ($reflection = $context->action) {
            // Extract parameters defined in the action
            $parameters = $reflection->getParameters();

            // Extract only parameters that are defined in the route
            $pattern = '/\/{([a-zA-Z_]+)(\??)}/';
            $matches = [];
            preg_match_all($pattern, $context->route->uri, $matches);
            return (new Collection($parameters))
                ->filter(fn(ReflectionParameter $parameter) => in_array($parameter->getName(), $matches[1]))
                ->map(function (ReflectionParameter $parameter) use ($context) {
                    $parameterDto = new EndpointParameterDto(
                        in: RouteParameterLocation::Path,
                        name: $parameter->getName(),
                    );

                    $parameterDto->required = !$parameter->getType()->allowsNull();
                    $parameterDto->schema = $this->_schemaService->createFromType($parameter->getType());

                    if (isset($context->route->routeDefinition->wheres[$parameter->getName()])) {
                        $parameterDto->schema->pattern = $context->route
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
