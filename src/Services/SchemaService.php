<?php

namespace IrealWorlds\OpenApi\Services;

use IrealWorlds\OpenApi\Models\Document\Schema\SchemaPropertyDto;
use ReflectionNamedType;
use ReflectionType;

class SchemaService
{
    public function createFromType(ReflectionType $type): SchemaPropertyDto {
        $output = new SchemaPropertyDto("string", "custom");


        // Try to figure out the type this parameter should have
        if ($type instanceof ReflectionNamedType) {
            switch ($type->getName()) {
                case "int": {
                    $output->type = "integer";
                    $output->format = "int32";
                    break;
                }
                case "float": {
                    $output->type = "number";
                    $output->format = "float";
                    break;
                }
                case "string": {
                    $output->type = "string";
                    $output->format = null;
                    break;
                }
                case "bool": {
                    $output->type = "boolean";
                    $output->format = null;
                    break;
                }
            }
        }

        // Set nullable
        if ($type->allowsNull()) {
            $output->nullable = true;
        }

        return $output;
    }
}