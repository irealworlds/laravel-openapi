<?php

namespace IrealWorlds\OpenApi\Contracts\Extractors;

use Illuminate\Support\Collection;

interface IRouteTagExtractor
{
    /**
     * Extract route tags.
     *
     * @return Collection<string>
     */
    public function extract(): Collection;
}
