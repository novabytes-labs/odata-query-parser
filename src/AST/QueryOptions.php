<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST;

use NovaBytes\OData\AST\Expand\ExpandItem;
use NovaBytes\OData\AST\OrderBy\OrderByItem;
use NovaBytes\OData\AST\Select\SelectItem;

readonly class QueryOptions implements Node
{
    /**
     * @param list<SelectItem>|null $select
     * @param list<ExpandItem>|null $expand
     * @param list<OrderByItem>|null $orderby
     */
    public function __construct(
        public ?Expression $filter = null,
        public ?array $select = null,
        public ?array $expand = null,
        public ?array $orderby = null,
        public ?int $top = null,
        public ?int $skip = null,
        public ?bool $count = null,
    ) {}
}
