<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Path;

use NovaBytes\OData\AST\Node;

/**
 * Represents a parsed OData resource path.
 *
 * For example, /Products(1)/Category is parsed as:
 * - entitySet: "Products"
 * - key: EntityKey with value 1
 * - navigationSegments: [NavigationSegment("Category")]
 */
readonly class ResourcePath implements Node
{
    /**
     * @param string $entitySet The entity set name (e.g., "Products").
     * @param EntityKey|null $key The entity key, if addressing a specific entity.
     * @param list<NavigationSegment> $navigationSegments Navigation property segments following the entity key.
     */
    public function __construct(
        public string $entitySet,
        public ?EntityKey $key = null,
        public array $navigationSegments = [],
    ) {}

    /**
     * Check whether this path addresses a specific entity (has a key).
     */
    public function isSingleEntity(): bool
    {
        return $this->key !== null;
    }

    /**
     * Check whether this path includes navigation segments.
     */
    public function hasNavigation(): bool
    {
        return $this->navigationSegments !== [];
    }
}
