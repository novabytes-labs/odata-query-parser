<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Path;

use NovaBytes\OData\AST\Node;

/**
 * Represents a navigation segment in an OData resource path.
 *
 * For example, in /Products(1)/Category or /Products(1)/Reviews(5),
 * "Category" and "Reviews(5)" are navigation segments.
 */
readonly class NavigationSegment implements Node
{
    /**
     * @param string $property The navigation property name.
     * @param EntityKey|null $key Optional key for addressing a specific entity in the navigation collection.
     */
    public function __construct(
        public string $property,
        public ?EntityKey $key = null,
    ) {}
}
