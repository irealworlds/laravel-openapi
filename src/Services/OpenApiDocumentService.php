<?php

namespace IrealWorlds\OpenApi\Services;


use IrealWorlds\OpenApi\Models\Document\OpenApiDocumentDto;

class OpenApiDocumentService
{
    /**
     * @throws \JsonException
     */
    public function createJsonDocument(OpenApiDocumentDto $document, string $path): void
    {
        file_put_contents($path, json_encode($document, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
    }
}