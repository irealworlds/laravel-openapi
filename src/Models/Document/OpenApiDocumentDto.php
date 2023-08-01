<?php

namespace IrealWorlds\OpenApi\Models\Document;

use IrealWorlds\OpenApi\Models\Document\Components\DocumentComponentsDto;
use IrealWorlds\OpenApi\Models\Document\Paths\PathEndpointDto;

class OpenApiDocumentDto
{
    /**
     * @param string $openapi
     * @param ApplicationInfoDto $info
     * @param array<string, array<string, PathEndpointDto>> $paths
     * @param DocumentComponentsDto $components
     */
    public function __construct(
        public readonly string $openapi,
        public readonly ApplicationInfoDto $info,
        public readonly array $paths,
        public readonly DocumentComponentsDto $components,
    ) {
    }
}