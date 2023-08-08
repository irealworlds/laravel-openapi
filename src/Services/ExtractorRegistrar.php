<?php

namespace IrealWorlds\OpenApi\Services;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use IrealWorlds\OpenApi\Contracts\Extractors\{IRouteParametersExtractor, IRouteSummaryExtractor, IRouteTagExtractor};
use IrealWorlds\OpenApi\Contracts\IExtractorRegistrar;
use IrealWorlds\OpenApi\Models\OpenApiRouteExtractionContext;

readonly class ExtractorRegistrar implements IExtractorRegistrar
{
    /**
     * A collection of currently registered route tags extractors.
     *
     * @var Collection<class-string<IRouteTagExtractor>|IRouteTagExtractor>
     */
    private Collection $_registeredRouteTagsExtractors;

    /**
     * A collection of currently registered route summary extractors.
     *
     * @var Collection<class-string<IRouteSummaryExtractor>|IRouteSummaryExtractor>
     */
    private Collection $_registeredRouteSummaryExtractors;

    /**
     * A collection of currently registered route summary extractors.
     *
     * @var Collection<class-string<IRouteParametersExtractor>|IRouteParametersExtractor>
     */
    private Collection $_registeredRouteParametersExtractors;

    /**
     * ExtractorRegistrar constructor method.
     *
     * @param Container $_container
     */
    public function __construct(
        private Container $_container
    ) {
        $this->_registeredRouteTagsExtractors = new Collection();
        $this->_registeredRouteSummaryExtractors = new Collection();
        $this->_registeredRouteParametersExtractors = new Collection();
    }

    /** @inheritDoc */
    public function setExtractionContext(OpenApiRouteExtractionContext $context): void
    {
        $this->_container->instance(OpenApiRouteExtractionContext::class, $context);
    }

    /** @inheritDoc */
    public function registerRouteTagsExtractor(string|IRouteTagExtractor $extractor): void
    {
        $this->_registeredRouteTagsExtractors
            ->prepend($extractor);
    }

    /** @inheritDoc */
    public function getRouteTagsExtractors(): Collection {
        return $this->_registeredRouteTagsExtractors->map(function ($extractor) {
            // If the extractor was passed as a class string, instantiate it
            if (is_string($extractor)) {
                $extractor = $this->_container->make($extractor);
            }
            return $extractor;
        });
    }

    /** @inheritDoc */
    public function registerRouteSummaryExtractor(string|IRouteSummaryExtractor $extractor): void
    {
        $this->_registeredRouteSummaryExtractors
            ->prepend($extractor);
    }

    /** @inheritDoc */
    public function getRouteSummaryExtractors(): Collection {
        return $this->_registeredRouteSummaryExtractors->map(function ($extractor) {
            // If the extractor was passed as a class string, instantiate it
            if (is_string($extractor)) {
                $extractor = $this->_container->make($extractor);
            }
            return $extractor;
        });
    }

    /** @inheritDoc */
    public function registerRouteParametersExtractor(string|IRouteParametersExtractor $extractor): void
    {
        $this->_registeredRouteParametersExtractors
            ->prepend($extractor);
    }

    /** @inheritDoc */
    public function getRouteParametersExtractors(): Collection {
        return $this->_registeredRouteParametersExtractors->map(function ($extractor) {
            // If the extractor was passed as a class string, instantiate it
            if (is_string($extractor)) {
                $extractor = $this->_container->make($extractor);
            }
            return $extractor;
        });
    }
}
