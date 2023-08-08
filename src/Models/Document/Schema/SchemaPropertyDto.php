<?php

namespace IrealWorlds\OpenApi\Models\Document\Schema;

use JsonSerializable;

class SchemaPropertyDto implements JsonSerializable
{
    /**
     * @param string $type
     * @param string|null $format
     * @param bool|null $nullable
     * @param mixed|null $default
     * @param string|null $pattern
     * @see https://swagger.io/docs/specification/data-models/data-types/
     */
    public function __construct(
        public string $type,
        public ?string $format = null,
        public ?bool $nullable = null,
        public mixed $default = null,
        public ?string $pattern = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $data = [
            'type' => $this->type
        ];

        foreach (['format', 'nullable', 'default', 'pattern'] as $property) {
            if ($this->{$property} !== null) {
                $data[$property] = $this->{$property};
            }
        }

        return $data;
    }
}
