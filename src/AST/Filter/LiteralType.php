<?php

declare(strict_types=1);

namespace NovaBytes\OData\AST\Filter;

enum LiteralType: string
{
    case Null = 'null';
    case Boolean = 'boolean';
    case Integer = 'integer';
    case Decimal = 'decimal';
    case String = 'string';
    case Guid = 'guid';
    case Date = 'date';
    case DateTimeOffset = 'datetimeoffset';
    case TimeOfDay = 'timeofday';
    case Duration = 'duration';
}
