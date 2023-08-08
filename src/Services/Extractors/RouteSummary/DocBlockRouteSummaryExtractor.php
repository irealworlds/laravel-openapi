<?php

namespace IrealWorlds\OpenApi\Services\Extractors\RouteSummary;

use IrealWorlds\OpenApi\Contracts\Extractors\IRouteSummaryExtractor;
use IrealWorlds\OpenApi\Models\OpenApiRouteExtractionContext;

/**
 * Extract the route summary from the first line of the action's DocBlock.
 */
class DocBlockRouteSummaryExtractor implements IRouteSummaryExtractor
{
    /**
     * @inheritDoc
     */
    public function extract(OpenApiRouteExtractionContext $context): string|null
    {
        if ($reflection = $context->action) {
            $comment = $reflection->getDocComment();

            if ($comment !== false) {

                // Remove comment delimiters (/* and */)
                $comment = mb_substr($comment, 3, -2);

                // Remove leading and trailing whitespace
                $comment = trim($comment);

                // Remove leading asterisks and additional spaces
                $comment = preg_replace('/^\s*\*+\s?/m', '', $comment);

                // Extract the summary from the doc block
                if (preg_match('/^([^\n]+)/', $comment, $matches)) {
                    $summary = $matches[1];
                } else {
                    // If no explicit summary found, use the first non-empty line as the summary
                    $lines = array_filter(explode("\n", $comment), 'trim');
                    $summary = trim($lines[0] ?? '');
                }

                return $summary;
            }
        }

        return null;
    }
}
