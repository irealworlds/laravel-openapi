<?php

namespace IrealWorlds\OpenApi\Services;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use IrealWorlds\OpenApi\Contracts\IExtractorRegistrar;
use IrealWorlds\OpenApi\Models\Document\Paths\PathEndpointDto;
use IrealWorlds\OpenApi\Models\Document\{ApplicationInfoDto, OpenApiDocumentDto, Paths\EndpointParameterDto, ServerDto};
use JsonException;
use ReflectionException;

readonly class OpenApiDocumentService
{
    public function __construct(
        private ConfigRepository $_configuration,
        private RouteService     $_routeService,
        private IExtractorRegistrar $_extractorRegistrar
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

            $extractionContext = $this->_routeService->getExtractorContextForRoute($route);
            $this->_extractorRegistrar->setExtractionContext($extractionContext);


            $endpoint = (new PathEndpointDto());

            // Extract tags
            foreach ($this->_extractorRegistrar->getRouteTagsExtractors() as $extractor) {
                $endpoint->addTags(...$extractor->extract());
            }
            $endpoint->tags = array_unique($endpoint->tags);

            // Extract the summary
            foreach ($this->_extractorRegistrar->getRouteSummaryExtractors() as $extractor) {
                if ($endpoint->summary = $extractor->extract()) {
                    break;
                }
            }

            // Extract the parameters
            foreach ($this->_extractorRegistrar->getRouteParametersExtractors() as $extractor) {
                foreach ($extractor->extract() as $parameter) {
                    $endpoint->addParameter($parameter);
                }
            }

            // Add the route to the document
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
        file_put_contents(
            $path,
            json_encode($document, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)
        );
    }
}
