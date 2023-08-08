<?php

namespace IrealWorlds\OpenApi\Models\Document;

use JsonSerializable;

readonly class ServerDto implements JsonSerializable
{
    public function __construct(
        public string      $url,
        public string|null $description = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        // Create an associative array with only the properties to include in the JSON output
        $data = ['url' => $this->url];

        // Add the description property only if it is not null
        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        return $data;
    }
}
