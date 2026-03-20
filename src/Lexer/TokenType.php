<?php

declare(strict_types=1);

namespace NovaBytes\OData\Lexer;

enum TokenType: string
{
    // Punctuation
    case OpenParen = '(';
    case CloseParen = ')';
    case Comma = ',';
    case Slash = '/';
    case Colon = ':';
    case Semicolon = ';';
    case Star = '*';
    case At = '@';
    case Equals = '=';

        // Literals
    case Integer = 'INTEGER';
    case Decimal = 'DECIMAL';
    case String = 'STRING';
    case Guid = 'GUID';
    case Date = 'DATE';
    case DateTimeOffset = 'DATETIMEOFFSET';
    case TimeOfDay = 'TIMEOFDAY';
    case Duration = 'DURATION';

        // Identifiers and keywords
    case Identifier = 'IDENTIFIER';
    case Null = 'null';
    case True = 'true';
    case False = 'false';

        // Comparison operators
    case Eq = 'eq';
    case Ne = 'ne';
    case Gt = 'gt';
    case Ge = 'ge';
    case Lt = 'lt';
    case Le = 'le';
    case In = 'in';
    case Has = 'has';

        // Logical operators
    case And = 'and';
    case Or = 'or';
    case Not = 'not';

        // Arithmetic operators
    case Add = 'add';
    case Sub = 'sub';
    case Mul = 'mul';
    case Div = 'div';
    case DivBy = 'divby';
    case Mod = 'mod';

        // Sort direction
    case Asc = 'asc';
    case Desc = 'desc';

        // Lambda
    case Any = 'any';
    case All = 'all';

        // Negation (unary minus)
    case Minus = '-';

        // Special
    case Dot = '.';
    case Eof = 'EOF';
}
