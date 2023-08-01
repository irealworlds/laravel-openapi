<?php

namespace IrealWorlds\OpenApi\Models\Document\Paths;

use JsonSerializable;

class PathEndpointDto implements JsonSerializable
{
    /**
     * @param array<string> $tags
     * @param array<EndpointParameterDto> $parameters
     * @param array<string|int, EndpointResponseDto> $responses
     */
    public function __construct(
        public array $tags = [],
        public array $parameters = [],
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

    /**
     * Add a new possible response to this endpoint.
     *
     * @param string|int $statusCode
     * @param EndpointResponseDto $response
     * @return $this
     */
    public function addResponse(string|int $statusCode, EndpointResponseDto $response): static
    {
        $this->responses[$statusCode] = $response;
        return $this;
    }

    /**
     * Add a new parameter to this endpoint definition.
     *
     * @param EndpointParameterDto $parameter
     * @return $this
     */
    public function addParameter(EndpointParameterDto $parameter): static {
        $this->parameters[] = $parameter;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $data = [
            'tags' => $this->tags,
            'responses' => (object) $this->responses,
        ];

        if (!empty($this->parameters)) {
            $data['parameters'] = $this->parameters;
        }

        return $data;
    }
}