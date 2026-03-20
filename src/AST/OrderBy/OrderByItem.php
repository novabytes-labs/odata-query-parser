<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\OrderBy;

use NovaBytes\OData\AST\Expression;
use NovaBytes\OData\AST\Node;

readonly class OrderByItem implements Node
{
    public function __construct(
        public Expression $expression,
        public SortDirection $direction = SortDirection::Asc,
    ) {}
}
