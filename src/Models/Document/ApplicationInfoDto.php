<?php

namespace IrealWorlds\OpenApi\Models\Document;

readonly class ApplicationInfoDto
{
    public function __construct(
        public string      $title,
        public string      $version,
        public string|null $description = null
    ) {
    }
}