<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Filter;

use NovaBytes\OData\AST\Expression;

readonly class Literal implements Expression
{
    public function __construct(
        public string|int|float|bool|null $value,
        public LiteralType $type,
    ) {}
}
