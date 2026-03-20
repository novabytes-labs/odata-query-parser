<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Filter;

use NovaBytes\OData\AST\Expression;

readonly class BinaryExpression implements Expression
{
    public function __construct(
        public Expression $left,
        public BinaryOperator $operator,
        public Expression $right,
    ) {}
}
