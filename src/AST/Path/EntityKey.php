<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Path;

use NovaBytes\OData\AST\Node;

/**
 * Represents a parsed OData entity key expression.
 *
 * Supports single keys like (1), ('abc'), and composite keys like (Key1=1,Key2='abc').
 */
readonly class EntityKey implements Node
{
    /**
     * @param array<string, int|float|string|bool|null> $values Key name-value pairs. For single unnamed keys, the key name is empty string.
     */
    public function __construct(
        public array $values,
    ) {}

    /**
     * Check whether this is a single (non-composite) key.
     */
    public function isSingle(): bool
    {
        return count($this->values) === 1;
    }

    /**
     * Get the single key value, or null if this is a composite key.
     */
    public function getSingleValue(): int|float|string|bool|null
    {
        if (!$this->isSingle()) {
            return null;
        }

        return array_values($this->values)[0];
    }
}
