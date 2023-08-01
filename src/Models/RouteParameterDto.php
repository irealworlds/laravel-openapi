<?php

namespace IrealWorlds\OpenApi\Models;

use ReflectionType;

readonly class RouteParameterDto
{
    public function __construct(
        public string  $name,
        public ReflectionType $type
    ) {
    }
}