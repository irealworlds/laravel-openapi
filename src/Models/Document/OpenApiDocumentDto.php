<?php

namespace IrealWorlds\OpenApi\Models\Document;

use IrealWorlds\OpenApi\Models\Document\Components\DocumentComponentsDto;
use IrealWorlds\OpenApi\Models\Document\Paths\PathEndpointDto;
use IrealWorlds\OpenApi\OpenApi;

class OpenApiDocumentDto
{
    public readonly string $openapi;

    /**
     * @param ApplicationInfoDto $info
     * @param array<string, array<string, PathEndpointDto>> $paths
     */
    public function __construct(
        public ApplicationInfoDto $info,
        public array $paths = [],
    ) {
        $this->openapi = OpenApi::Version;
    }

    /**
     * Set the application info for this document.
     *
     * @param ApplicationInfoDto $info
     * @return $this
     */
    public function setInfo(ApplicationInfoDto $info): static
    {
        $this->info = $info;
        return $this;
    }

    /**
     * Add a path to the document
     *
     * @param string $path
     * @param string $method
     * @param PathEndpointDto $endpoint
     * @return $this
     */
    public function addPath(string $path, string $method, PathEndpointDto $endpoint): static
    {
        // Make sure the path has leading and trailing slashes
        $path = trim($path, '/');
        $path = "/" . $path . "/";

        // Normalize the method to lowercase
        $method = mb_strtolower($method);

        // Make sure the path exists in the array
        if (!isset($this->paths[$path])) {
            $this->paths[$path] = [];
        }

        // Set the endpoint data
        $this->paths[$path][$method] = $endpoint;

        return $this;
    }
}