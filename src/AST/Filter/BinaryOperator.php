<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Filter;

enum BinaryOperator: string
{
    // Comparison
    case Eq = 'eq';
    case Ne = 'ne';
    case Gt = 'gt';
    case Ge = 'ge';
    case Lt = 'lt';
    case Le = 'le';
    case In = 'in';
    case Has = 'has';

    // Logical
    case And = 'and';
    case Or = 'or';

    // Arithmetic
    case Add = 'add';
    case Sub = 'sub';
    case Mul = 'mul';
    case Div = 'div';
    case DivBy = 'divby';
    case Mod = 'mod';
}
