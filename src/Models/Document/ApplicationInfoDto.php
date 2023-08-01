<?php

namespace IrealWorlds\OpenApi\Models\Document;

class ApplicationInfoDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $version,
        public readonly string|null $description = null
    ) {
    }
}