<?php

namespace IrealWorlds\OpenApi\Models;

use ReflectionType;

class RouteParameterDto
{
    public function __construct(
        public string  $name,
        public ReflectionType $type,
        public string|null $pattern = null,
        public mixed $defaultValue = null
    ) {
    }
}