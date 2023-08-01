<?php

namespace IrealWorlds\OpenApi\Models\Document\Components;

class DocumentComponentsDto
{
    /**
     * @param array<string, SchemaComponentDto> $schemas
     * @param array<string, mixed> $securitySchemes
     */
    public function __construct(
        public readonly array $schemas = [],
        public readonly array $securitySchemes = []
    ) {
    }
}