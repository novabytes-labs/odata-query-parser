<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\OrderBy;

enum SortDirection: string
{
    case Asc = 'asc';
    case Desc = 'desc';
}
