<?php

namespace IrealWorlds\OpenApi\Models\Document\Schema;

use IrealWorlds\OpenApi\Contracts\ISchemaProperty;
use JsonSerializable;

class ReferenceSchemaProperty implements ISchemaProperty, JsonSerializable
{
    /**
     * ReferenceSchemaProperty constructor method.
     *
     * @param string $reference
     * @see https://swagger.io/docs/specification/using-ref/
     */
    public function __construct(
        public string $reference
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            '$ref' => $this->reference
        ];
    }
}