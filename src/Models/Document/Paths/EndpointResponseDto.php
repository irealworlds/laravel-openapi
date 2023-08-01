<?php

namespace IrealWorlds\OpenApi\Models\Document\Paths;

class EndpointResponseDto
{
    public function __construct(
        public readonly string $description,
    ) {
    }
}