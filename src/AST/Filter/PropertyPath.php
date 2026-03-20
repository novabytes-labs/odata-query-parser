<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Filter;

use NovaBytes\OData\AST\Expression;

readonly class PropertyPath implements Expression
{
    /** @param list<string> $segments */
    public function __construct(
        public array $segments,
    ) {}

    public function __toString(): string
    {
        return implode('/', $this->segments);
    }
}
