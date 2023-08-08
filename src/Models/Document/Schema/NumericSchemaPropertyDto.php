<?php

namespace IrealWorlds\OpenApi\Models\Document\Schema;

class NumericSchemaPropertyDto extends SchemaPropertyDto
{
    /**
     * @param string $type
     * @param string|null $format
     * @param bool|null $nullable
     * @param int|float|null $default
     * @param string|null $pattern
     * @param int|float|null $minimum
     * @param int|float|null $maximum
     * @param bool|null $exclusiveMinimum
     * @param int|float|null $multipleOf
     */
    public function __construct(
        string $type = 'number',
        ?string $format = null,
        ?bool $nullable = null,
        int|float|null $default = null,
        ?string $pattern = null,
        public int|float|null $minimum = null,
        public int|float|null $maximum = null,
        public bool|null $exclusiveMinimum = null,
        public int|float|null $multipleOf = null,
    ) {
        parent::__construct($type, $format, $nullable, $default, $pattern);
    }

    /** @inheritDoc */
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        foreach (['minimum', 'maximum', 'exclusiveMinimum', 'multipleOf'] as $property) {
            if (!empty($this->{$property})) {
                $data[$property] = $this->{$property};
            }
        }

        return $data;
    }
}
