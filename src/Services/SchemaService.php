<?php

namespace IrealWorlds\OpenApi\Services;

use IrealWorlds\OpenApi\Models\Document\Schema\{ArraySchemaPropertyDto,
    BooleanSchemaPropertyDto,
    NumericSchemaPropertyDto,
    SchemaPropertyDto,
    StringSchemaPropertyDto};
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
                    $output = new NumericSchemaPropertyDto(
                        type: 'integer',
                        format: 'int32'
                    );
                    break;
                }
                case "float": {
                    $output = new NumericSchemaPropertyDto(
                        type: 'number',
                        format: 'float'
                    );
                    break;
                }
                case "string": {
                    $output = new StringSchemaPropertyDto();
                    break;
                }
                case "bool": {
                    $output = new BooleanSchemaPropertyDto();
                    break;
                }
                case "array": {
                    $output = new ArraySchemaPropertyDto();
                    break;
                }
                default: {
                    $output = new SchemaPropertyDto("string", "custom");
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