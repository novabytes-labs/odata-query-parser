<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Filter;

enum UnaryOperator: string
{
    case Not = 'not';
    case Negate = '-';
}
