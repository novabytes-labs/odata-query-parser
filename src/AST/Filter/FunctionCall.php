<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Filter;

use NovaBytes\OData\AST\Expression;

readonly class FunctionCall implements Expression
{
    /** @param list<Expression> $arguments */
    public function __construct(
        public string $name,
        public array $arguments,
    ) {}
}
