<?php

namespace IrealWorlds\OpenApi\Models\Document\Schema;

class BooleanSchemaPropertyDto extends SchemaPropertyDto
{
    /**
     * @param string|null $format
     * @param bool|null $nullable
     * @param bool|null $default
     * @param string|null $pattern
     */
    public function __construct(
        ?string $format = null,
        ?bool $nullable = null,
        bool|null $default = null,
        ?string $pattern = null
    ) {
        parent::__construct('boolean', $format, $nullable, $default, $pattern);
    }
}
