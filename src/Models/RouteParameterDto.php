<?php

namespace IrealWorlds\OpenApi\Models;

readonly class RouteParameterDto
{
    public function __construct(
        public string  $name,
        public bool $required = true,
    ) {
    }
}