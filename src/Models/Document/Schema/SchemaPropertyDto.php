<?php

namespace IrealWorlds\OpenApi\Models\Document\Schema;

class SchemaPropertyDto implements \JsonSerializable
{
    public function __construct(
        public string $type,
        public ?string $format = null,
        public ?bool $nullable = null,
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

        foreach (['format', 'nullable', 'pattern'] as $property) {
            if ($this->{$property} !== null) {
                $data[$property] = $this->{$property};
            }
        }

        return $data;
    }
}