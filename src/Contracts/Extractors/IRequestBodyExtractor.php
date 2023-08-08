<?php

namespace IrealWorlds\OpenApi\Contracts\Extractors;

use Illuminate\Support\Collection;
use IrealWorlds\OpenApi\Models\OpenApiRouteExtractionContext;

interface IRequestBodyExtractor
{
    /**
     * Extract the request body that should be sent to this action.
     *
     * @param OpenApiRouteExtractionContext $context
     * @return Collection<string>
     */
    public function extract(OpenApiRouteExtractionContext $context): Collection;
}