<?php

namespace IrealWorlds\OpenApi\Services;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use IrealWorlds\OpenApi\Enums\RouteParameterLocation;
use IrealWorlds\OpenApi\Models\Document\Paths\PathEndpointDto;
use IrealWorlds\OpenApi\Models\Document\{ApplicationInfoDto, OpenApiDocumentDto, Paths\EndpointParameterDto, ServerDto};
use JsonException;
use ReflectionException;

readonly class OpenApiDocumentService
{
    public function __construct(
        private ConfigRepository $_configuration,
        private RouteService     $_routeService,
        private SchemaService    $_schemaService
    ) {
    }

    /**
     * Create a new {@link OpenApiDocumentDto} for the current application state.
     *
     * @return OpenApiDocumentDto
     * @throws ReflectionException
     */
    public function createDocument(): OpenApiDocumentDto {
        // Create a new document
        $document = new OpenApiDocumentDto(
            new ApplicationInfoDto(
                $this->_configuration->get('openapi.app_name'),
                $this->_configuration->get('openapi.app_version'),
                $this->_configuration->get('openapi.app_description'),
            )
        );

        // Add servers
        foreach ($this->_configuration->get('openapi.servers') as $server) {
            $document->addServer($server);
        }

        // Add registered documents as paths to the document
        foreach ($this->_routeService->getRegisteredRoutes() as $route) {
            // If the method is ignored, skip this route.
            if (in_array(mb_strtolower($route->method), $this->_configuration->get('openapi.ignored_methods'))) {
                continue;
            }

            $endpoint = (new PathEndpointDto())
                ->addTags(...$route->tags);

            // Add summary
            $endpoint->summary = $route->summary;

            // Add parameters
            foreach ($route->parameters as $parameter) {
                $parameterDto = new EndpointParameterDto(
                    RouteParameterLocation::Path,
                    $parameter->name,
                );
                $parameterDto->required = !$parameter->type->allowsNull();
                $parameterDto->schema = $this->_schemaService->createFromType($parameter->type);
                $parameterDto->schema->pattern = $parameter->pattern;
                $parameterDto->schema->default = $parameter->defaultValue;

                $endpoint->addParameter($parameterDto);
            }

            $document->addPath(
                $route->uri,
                $route->method,
                $endpoint
            );
        }

        return $document;
    }

    /**
     * @throws JsonException
     */
    public function createJsonDocument(OpenApiDocumentDto $document, string $path): void
    {
        file_put_contents($path, json_encode($document, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
    }
}