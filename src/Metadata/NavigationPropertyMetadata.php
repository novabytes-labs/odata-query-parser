<?php

declare(strict_types=1);

namespace NovaBytes\OData\Metadata;

/**
 * Represents an OData navigation property (relationship to another entity type).
 */
readonly class NavigationPropertyMetadata
{
    /**
     * @param string $name PascalCase navigation property name (e.g., "Reviews").
     * @param string $targetEntityType Target entity type name (e.g., "Review").
     * @param bool $isCollection Whether this is a collection navigation property.
     */
    public function __construct(
        public string $name,
        public string $targetEntityType,
        public bool $isCollection,
    ) {}
}
