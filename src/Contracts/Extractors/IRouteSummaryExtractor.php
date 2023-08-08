<?php

namespace IrealWorlds\OpenApi\Contracts\Extractors;

interface IRouteSummaryExtractor
{
    /**
     * Extract the route summary for the current context.
     *
     * @return string|null
     */
    public function extract(): string|null;
}
