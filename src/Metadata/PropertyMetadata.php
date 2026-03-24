<?php

declare(strict_types=1);

namespace NovaBytes\OData\Metadata;

/**
 * Represents an OData entity property with its type and query capabilities.
 */
readonly class PropertyMetadata
{
    /**
     * @param string $name PascalCase property name (e.g., "CategoryId").
     * @param string $edmType OData EDM type (e.g., "Edm.String").
     * @param bool $nullable Whether the property allows null values.
     * @param bool $filterable Whether the property can be used in $filter.
     * @param bool $sortable Whether the property can be used in $orderby.
     * @param bool $selectable Whether the property can be used in $select.
     */
    public function __construct(
        public string $name,
        public string $edmType,
        public bool $nullable,
        public bool $filterable = false,
        public bool $sortable = false,
        public bool $selectable = false,
    ) {}
}
