<?php

declare(strict_types=1);

namespace NovaBytes\OData\Metadata;

/**
 * Represents an OData entity type with its properties and navigation properties.
 */
readonly class EntityType
{
    /**
     * @param string $name Entity type name (e.g., "Product").
     * @param string $entitySetName Entity set name (e.g., "Products").
     * @param string $keyProperty Primary key property name in PascalCase (e.g., "Id").
     * @param list<PropertyMetadata> $properties Structural properties.
     * @param list<NavigationPropertyMetadata> $navigationProperties Navigation properties.
     */
    public function __construct(
        public string $name,
        public string $entitySetName,
        public string $keyProperty,
        public array $properties,
        public array $navigationProperties = [],
    ) {}
}
