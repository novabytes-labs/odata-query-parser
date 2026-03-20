<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Filter;

use NovaBytes\OData\AST\Expression;

readonly class UnaryExpression implements Expression
{
    public function __construct(
        public UnaryOperator $operator,
        public Expression $operand,
    ) {}
}
