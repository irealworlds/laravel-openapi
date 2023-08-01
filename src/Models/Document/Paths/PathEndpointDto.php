<?php

namespace IrealWorlds\OpenApi\Models\Document\Paths;

class PathEndpointDto
{
    /**
     * @param array<string> $tags
     * @param array<string|int, EndpointResponseDto> $responses
     */
    public function __construct(
        public array $tags = [],
        public array $responses = [],
    ) {
    }

    /**
     * @param string[] $tags
     * @return $this
     */
    public function addTags(...$tags): static
    {
        $this->tags = array_merge($this->tags, $tags);
        return $this;
    }

    public function addResponse(string|int $statusCode, EndpointResponseDto $response): static
    {
        $this->responses[$statusCode] = $response;
        return $this;
    }
}