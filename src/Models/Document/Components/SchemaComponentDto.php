<?php

namespace IrealWorlds\OpenApi\Models\Document\Components;

class SchemaComponentDto
{
    /**
     * @param string $type
     * @param array<string, SchemaComponentPropertyDto> $properties
     */
    public function __construct(
        public readonly string $type,
        public readonly array $properties
    ) {
    }
}