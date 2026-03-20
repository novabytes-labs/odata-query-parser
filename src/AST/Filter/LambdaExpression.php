<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Filter;

use NovaBytes\OData\AST\Expression;

readonly class LambdaExpression implements Expression
{
    public function __construct(
        public Expression $collection,
        public LambdaOperator $operator,
        public ?string $variable,
        public ?Expression $predicate,
    ) {}
}
