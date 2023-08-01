<?php

namespace IrealWorlds\OpenApi\Models\Document\Paths;

use IrealWorlds\OpenApi\Enums\RouteParameterLocation;
use IrealWorlds\OpenApi\Models\Document\Schema\SchemaPropertyDto;
use JsonSerializable;

class EndpointParameterDto implements JsonSerializable
{
    /**
     * @param RouteParameterLocation $in
     * @param string $name
     * @param SchemaPropertyDto|null $schema
     * @param string|null $description
     * @param bool $required
     */
    public function __construct(
        public RouteParameterLocation $in,
        public string $name,
        public SchemaPropertyDto|null  $schema = null,
        public string|null $description = null,
        public bool   $required = true,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $data = [
            "name" => $this->name,
            "in" => $this->in->value,
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