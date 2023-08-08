<?php

namespace IrealWorlds\OpenApi\Services;

use IrealWorlds\OpenApi\Models\Document\Schema\{ArraySchemaPropertyDto,
    BooleanSchemaPropertyDto,
    NumericSchemaPropertyDto,
    SchemaPropertyDto,
    StringSchemaPropertyDto};
use IrealWorlds\OpenApi\Contracts\ISchemaProperty;
use ReflectionNamedType;

class SchemaService
{
    /**
     * Create a new schema property from a reflection type.
     *
     * @param ReflectionNamedType $type
     * @return ISchemaProperty
     */
    public function createFromType(ReflectionNamedType $type): ISchemaProperty {

        // Try to figure out the type this parameter should have
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

        // Set nullable
        if ($type->allowsNull()) {
            $output->nullable = true;
        }

        return $output;
    }
}