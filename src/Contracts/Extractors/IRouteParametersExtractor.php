<?php

namespace IrealWorlds\OpenApi\Contracts\Extractors;

use Illuminate\Support\Collection;
use IrealWorlds\OpenApi\Models\Document\Paths\EndpointParameterDto;
use IrealWorlds\OpenApi\Models\OpenApiRouteExtractionContext;

interface IRouteParametersExtractor
{
    /**
     * Extract the parameters for the current route context.
     *
     * @note Only parameters that should be present in the PATH, QUERY, HEADERS or COOKIES should be extracted.
     * @see https://swagger.io/docs/specification/describing-parameters/
     * @param OpenApiRouteExtractionContext $context
     * @return Collection<EndpointParameterDto>
     */
    public function extract(OpenApiRouteExtractionContext $context): Collection;
}
