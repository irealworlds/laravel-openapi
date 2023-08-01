<?php

namespace IrealWorlds\OpenApi\Models\Document\Paths;

use IrealWorlds\OpenApi\Enums\RouteParameterLocation;

readonly class EndpointParameterDto implements \JsonSerializable
{
    /**
     * @param string $in
     * @param string $name
     * @param mixed $schema
     * @param string $description
     * @param bool $required
     */
    public function __construct(
        public RouteParameterLocation $in,
        public string $name,
        public mixed  $schema = "",
        public string $description = "",
        public bool   $required = true
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $data = [
            "in" => $this->in->value,
            "name" => $this->name,
            "required" => $this->required,
        ];

        if (!empty($this->schema)) {
            $data['schema'] = $this->schema;
        }

        if (!empty($this->description)) {
            $data['description'] = $this->description;
        }

        return $data;
    }
}