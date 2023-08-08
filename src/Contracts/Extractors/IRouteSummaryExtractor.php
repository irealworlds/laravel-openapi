<?php

namespace IrealWorlds\OpenApi\Contracts\Extractors;

use IrealWorlds\OpenApi\Models\OpenApiRouteExtractionContext;

interface IRouteSummaryExtractor
{
    /**
     * Extract the route summary for the current context.
     *
     * @param OpenApiRouteExtractionContext $context
     * @return string|null
     */
    public function extract(OpenApiRouteExtractionContext $context): string|null;
}
