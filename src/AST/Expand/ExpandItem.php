<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Expand;

use NovaBytes\OData\AST\Node;
use NovaBytes\OData\AST\QueryOptions;

readonly class ExpandItem implements Node
{
    /**
     * @param list<string> $path
     */
    public function __construct(
        public array $path,
        public bool $isWildcard = false,
        public ?QueryOptions $nestedOptions = null,
    ) {}

    public function __toString(): string
    {
        if ($this->isWildcard) {
            return '*';
        }
        return implode('/', $this->path);
    }
}
