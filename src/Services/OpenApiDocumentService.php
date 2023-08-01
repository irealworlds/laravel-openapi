<?php

namespace IrealWorlds\OpenApi\Services;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use IrealWorlds\OpenApi\Models\Document\Paths\PathEndpointDto;
use IrealWorlds\OpenApi\Models\Document\{ApplicationInfoDto, OpenApiDocumentDto};

readonly class OpenApiDocumentService
{
    public function __construct(
        private ConfigRepository $_configuration,
        private RouteService     $_routeService
    ) {
    }

    /**
     * Create a new {@link OpenApiDocumentDto} for the current application state.
     *
     * @return OpenApiDocumentDto
     */
    public function createDocument(): OpenApiDocumentDto {
        // Create a new document
        $document = new OpenApiDocumentDto(
            new ApplicationInfoDto(
                $this->_configuration->get('openapi.app_name'),
                $this->_configuration->get('openapi.app_version'),
                $this->_configuration->get('openapi.app_description'),
            ),
        );

        // Add registered documents as paths to the document
        foreach ($this->_routeService->getRegisteredRoutes() as $route) {
            // If the method is ignored, skip this route.
            if (in_array(mb_strtolower($route->method), $this->_configuration->get('openapi.ignored_methods'))) {
                continue;
            }

            $document->addPath(
                $route->uri,
                $route->method,
                (new PathEndpointDto())
                    ->addTags(...$route->tags)
            );
        }

        return $document;
    }

    /**
     * @throws \JsonException
     */
    public function createJsonDocument(OpenApiDocumentDto $document, string $path): void
    {
        file_put_contents($path, json_encode($document, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
    }
}