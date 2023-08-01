<?php

namespace IrealWorlds\OpenApi\Models\Document\Components;

class SchemaComponentPropertyDto
{
    /**
     * @param string $type
     * @param bool $nullable
     * @param bool $readOnly
     */
    public function __construct(
        public readonly string $type,
        public readonly bool $nullable = true,
        public readonly bool $readOnly = false,
    ) {
    }
}