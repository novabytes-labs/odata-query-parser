<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Filter;

use NovaBytes\OData\AST\Expression;

readonly class ListExpression implements Expression
{
    /** @param list<Expression> $items */
    public function __construct(
        public array $items,
    ) {}
}
