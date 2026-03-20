<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Filter;

enum LambdaOperator: string
{
    case Any = 'any';
    case All = 'all';
}
