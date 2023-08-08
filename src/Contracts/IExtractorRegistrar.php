<?php

namespace IrealWorlds\OpenApi\Contracts;

use Illuminate\Support\Collection;
use IrealWorlds\OpenApi\Contracts\Extractors\{IRouteParametersExtractor, IRouteSummaryExtractor, IRouteTagExtractor};
use IrealWorlds\OpenApi\Models\OpenApiRouteExtractionContext;

interface IExtractorRegistrar
{
    /**
     * Set a new route extraction context.
     *
     * @param OpenApiRouteExtractionContext $context
     * @return void
     */
    public function setExtractionContext(OpenApiRouteExtractionContext $context): void;

    /**
     * Register a new route tags extractor.
     *
     * @template TExtractor of IRouteTagExtractor
     * @param class-string<TExtractor>|IRouteTagExtractor $extractor
     * @return void
     */
    public function registerRouteTagsExtractor(string|IRouteTagExtractor $extractor): void;

    /**
     * Get a list of registered route tags extractors.
     *
     * @return Collection<IRouteTagExtractor>
     */
    public function getRouteTagsExtractors(): Collection;

    /**
     * Register a new route summary extractor.
     *
     * @template TExtractor of IRouteSummaryExtractor
     * @param class-string<TExtractor>|IRouteSummaryExtractor $extractor
     * @return void
     */
    public function registerRouteSummaryExtractor(string|IRouteSummaryExtractor $extractor): void;

    /**
     * Get a list of registered route summary extractors.
     *
     * @return Collection<IRouteSummaryExtractor>
     */
    public function getRouteSummaryExtractors(): Collection;

    /**
     * Register a new route parameters extractor.
     *
     * @template TExtractor of IRouteParametersExtractor
     * @param class-string<TExtractor>|IRouteParametersExtractor $extractor
     * @return void
     */
    public function registerRouteParametersExtractor(string|IRouteParametersExtractor $extractor): void;

    /**
     * Get a list of registered route parameters extractors.
     *
     * @return Collection<IRouteParametersExtractor>
     */
    public function getRouteParametersExtractors(): Collection;
}
