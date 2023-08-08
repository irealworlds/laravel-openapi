<?php

namespace IrealWorlds\OpenApi\Contracts\Extractors;

use Illuminate\Support\Collection;
use IrealWorlds\OpenApi\Models\OpenApiRouteExtractionContext;

interface IRouteTagExtractor
{
    /**
     * Extract route tags.
     *
     * @param OpenApiRouteExtractionContext $context
     * @return Collection<string>
     */
    public function extract(OpenApiRouteExtractionContext $context): Collection;
}
