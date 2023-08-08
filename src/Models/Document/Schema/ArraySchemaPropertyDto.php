<?php

namespace IrealWorlds\OpenApi\Models\Document\Schema;

class ArraySchemaPropertyDto extends SchemaPropertyDto
{
    public function __construct(
        public array $items = [],
        ?string $format = null,
        ?bool $nullable = null,
        mixed $default = null,
        ?string $pattern = null,
        public int|null $minItems = null,
        public int|null $maxItems = null,
        public bool|null $uniqueItems = null,
    ) {
        parent::__construct('array', $format, $nullable, $default, $pattern);
    }

    public function jsonSerialize(): array
    {
        $data = [
            'type' => $this->type,
            'items' => $this->items,
        ];

        foreach (['minItems', 'maxItems', 'uniqueItems'] as $property) {
            if (!empty($this->{$property})) {
                $data[$property] = $this->{$property};
            }
        }

        return $data;
    }
}
