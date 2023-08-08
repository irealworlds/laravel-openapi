<?php

namespace IrealWorlds\OpenApi\Models\Document\Schema;

class StringSchemaPropertyDto extends SchemaPropertyDto
{
    /**
     * @param 'date'|'date-time'|'password'|'byte'|'binary'|null $format
     * @param bool|null $nullable
     * @param string|null $default
     * @param string|null $pattern
     * @param int|null $minLength
     * @param int|null $maxLength
     */
    public function __construct(
        ?string $format = null,
        ?bool $nullable = null,
        string|null $default = null,
        ?string $pattern = null,
        public int|null $minLength = null,
        public int|null $maxLength = null,
    ) {
        parent::__construct('string', $format, $nullable, $default, $pattern);
    }

    /** @inheritDoc */
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();

        foreach (['minLength', 'maxLength'] as $property) {
            if (!empty($this->{$property})) {
                $data[$property] = $this->{$property};
            }
        }

        return $data;
    }
}
