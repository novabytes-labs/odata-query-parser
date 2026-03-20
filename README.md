# OData Query Parser for PHP

A framework-agnostic OData 4 query string parser for PHP 8.2+. Parses `$filter`, `$select`, `$expand`, `$orderby`, `$top`, `$skip`, and `$count` into immutable AST objects.

Zero runtime dependencies.

## Installation

```bash
composer require novabytes/odata-query-parser
```

## Quick Start

```php
use NovaBytes\OData\Parser\QueryOptionParser;

$query = QueryOptionParser::parse(
    '$filter=Price gt 100 and contains(Name,\'Widget\')'
    . '&$select=Name,Price'
    . '&$expand=Category($select=Name;$top=5)'
    . '&$orderby=Price desc'
    . '&$top=50&$skip=10&$count=true'
);

$query->filter;   // BinaryExpression (and)
$query->select;   // [SelectItem('Name'), SelectItem('Price')]
$query->expand;   // [ExpandItem('Category', nestedOptions: ...)]
$query->orderby;  // [OrderByItem(PropertyPath('Price'), Desc)]
$query->top;      // 50
$query->skip;     // 10
$query->count;    // true
```

You can also parse individual query options directly:

```php
use NovaBytes\OData\Parser\FilterParser;
use NovaBytes\OData\Parser\SelectParser;
use NovaBytes\OData\Parser\OrderByParser;

$filter = FilterParser::parse('Price gt 100 and contains(Name,\'Widget\')');
$select = SelectParser::parse('Name,Price,Address/City');
$orderby = OrderByParser::parse('Name asc,Price desc');
```

## Supported Query Options

### `$filter`

Full expression language with correct operator precedence:

```php
// Comparison operators: eq, ne, gt, ge, lt, le
FilterParser::parse('Price gt 100');
FilterParser::parse('Name eq \'Milk\'');

// Logical operators: and, or, not
FilterParser::parse('Price gt 5 and Price lt 20');
FilterParser::parse('not contains(Name,\'test\')');

// Arithmetic operators: add, sub, mul, div, divby, mod
FilterParser::parse('Price mul Quantity gt 1000');

// Grouping with parentheses
FilterParser::parse('(Name eq \'A\' or Name eq \'B\') and Price lt 10');

// Property paths
FilterParser::parse('Address/City eq \'London\'');

// The in operator
FilterParser::parse('Name in (\'Milk\',\'Cheese\',\'Butter\')');
```

**30+ built-in functions:**

```php
// String functions
FilterParser::parse('contains(Name,\'milk\')');
FilterParser::parse('startswith(Name,\'Ch\')');
FilterParser::parse('endswith(Name,\'ilk\')');
FilterParser::parse('length(Name) gt 5');
FilterParser::parse('indexof(Name,\'lk\') eq 2');
FilterParser::parse('substring(Name,1,3) eq \'ilk\'');
FilterParser::parse('tolower(Name) eq \'milk\'');
FilterParser::parse('toupper(Name) eq \'MILK\'');
FilterParser::parse('trim(Name) eq \'Milk\'');
FilterParser::parse('concat(FirstName,LastName) eq \'JohnDoe\'');

// Date/time functions
FilterParser::parse('year(BirthDate) eq 1990');
FilterParser::parse('month(BirthDate) eq 3');
FilterParser::parse('day(BirthDate) eq 20');
FilterParser::parse('hour(StartTime) ge 9');
FilterParser::parse('Date gt now()');

// Math functions
FilterParser::parse('round(Price) eq 10');
FilterParser::parse('floor(Price) eq 9');
FilterParser::parse('ceiling(Price) eq 10');
```

**Lambda expressions:**

```php
// any — true if any element matches
FilterParser::parse('Items/any(d:d/Qty gt 100)');

// any without predicate — true if collection is non-empty
FilterParser::parse('Tags/any()');

// all — true if all elements match
FilterParser::parse('Items/all(d:d/Price gt 0)');
```

**All literal types:**

```php
FilterParser::parse('Active eq true');                                    // boolean
FilterParser::parse('Name eq null');                                      // null
FilterParser::parse('Count eq 42');                                       // integer
FilterParser::parse('Price lt 9.99');                                     // decimal
FilterParser::parse('Name eq \'O\'\'Brien\'');                            // string (escaped quotes)
FilterParser::parse('Id eq 01234567-89ab-cdef-0123-456789abcdef');        // GUID
FilterParser::parse('BirthDate eq 2023-01-15');                           // date
FilterParser::parse('Created eq 2023-01-15T14:30:00Z');                   // DateTimeOffset
FilterParser::parse('Duration eq duration\'P1DT2H30M\'');                 // duration
```

### `$select`

```php
$items = SelectParser::parse('Name,Price,Address/City');
// [SelectItem(['Name']), SelectItem(['Price']), SelectItem(['Address', 'City'])]

$items = SelectParser::parse('*');
// [SelectItem([], isWildcard: true)]
```

### `$expand`

```php
use NovaBytes\OData\Parser\ExpandParser;

$items = ExpandParser::parse('Products,Category');
// [ExpandItem(['Products']), ExpandItem(['Category'])]

// With nested query options (semicolon-separated inside parentheses)
$items = ExpandParser::parse('Products($filter=Price gt 100;$select=Name;$top=5)');
// ExpandItem(['Products'], nestedOptions: QueryOptions(filter: ..., select: ..., top: 5))
```

### `$orderby`

```php
$items = OrderByParser::parse('Name asc,Price desc');
// [OrderByItem(PropertyPath('Name'), Asc), OrderByItem(PropertyPath('Price'), Desc)]

// Default direction is ascending
$items = OrderByParser::parse('Name');
// [OrderByItem(PropertyPath('Name'), Asc)]
```

### `$top`, `$skip`, `$count`

Parsed as part of `QueryOptionParser::parse()`:

```php
$query = QueryOptionParser::parse('$top=10&$skip=20&$count=true');
$query->top;   // 10
$query->skip;  // 20
$query->count; // true
```

## AST Structure

Every parsed result is an immutable (`readonly class`) AST node. The `$filter` expression tree uses these node types:

| Node | Description |
|------|-------------|
| `BinaryExpression` | `left operator right` (e.g. `Price gt 100`, `A and B`) |
| `UnaryExpression` | `operator operand` (e.g. `not expr`, `-5`) |
| `PropertyPath` | Dotted/slashed property reference (e.g. `Address/City`) |
| `Literal` | Typed value: null, boolean, integer, decimal, string, GUID, date, etc. |
| `FunctionCall` | Built-in function with arguments (e.g. `contains(Name,'x')`) |
| `LambdaExpression` | `collection/any(var:predicate)` or `collection/all(var:predicate)` |
| `ListExpression` | Parenthesized list for `in` operator (e.g. `('A','B','C')`) |

## Visitor Pattern

Implement `ExpressionVisitor` to transform the AST into whatever you need:

```php
use NovaBytes\OData\AST\Filter\BinaryExpression;
use NovaBytes\OData\AST\Filter\Literal;
use NovaBytes\OData\AST\Filter\PropertyPath;
use NovaBytes\OData\Visitor\ExpressionVisitor;

class SqlWhereVisitor implements ExpressionVisitor
{
    public function visitBinaryExpression(BinaryExpression $expr): string
    {
        $left = $this->visit($expr->left);
        $right = $this->visit($expr->right);
        $op = match($expr->operator) {
            BinaryOperator::Eq => '=',
            BinaryOperator::Ne => '!=',
            BinaryOperator::Gt => '>',
            // ...
        };
        return "{$left} {$op} {$right}";
    }

    public function visitPropertyPath(PropertyPath $expr): string
    {
        return implode('.', $expr->segments);
    }

    public function visitLiteral(Literal $expr): string
    {
        // Use parameterized queries in real code!
        return match($expr->type) {
            LiteralType::String => "'{$expr->value}'",
            LiteralType::Null => 'NULL',
            default => (string) $expr->value,
        };
    }

    // ... implement remaining visit methods
}
```

A `StringifyVisitor` is included for round-tripping AST back to OData syntax:

```php
use NovaBytes\OData\Visitor\StringifyVisitor;

$expr = FilterParser::parse('Price gt 100 and Name eq \'Milk\'');
$visitor = new StringifyVisitor();
echo $visitor->stringify($expr);
// Price gt 100 and Name eq 'Milk'
```

## Error Handling

All parse errors throw `NovaBytes\OData\Exception\ParseException` with position information:

```php
try {
    FilterParser::parse('Price gtt 100');
} catch (ParseException $e) {
    $e->getMessage();  // "Unexpected 'gtt' at position 6; expected ..."
    $e->position;      // 6
}
```

## OData 4 Support

### System Query Options

| Query Option | Status | Notes |
|---|---|---|
| `$filter` | Supported | Full expression language with correct operator precedence |
| `$select` | Supported | Property paths, wildcards (`*`), nested options |
| `$expand` | Supported | Navigation paths, nested query options (`$filter`, `$select`, `$top`, etc.) |
| `$orderby` | Supported | Expressions with `asc`/`desc`, multiple sort keys |
| `$top` | Supported | |
| `$skip` | Supported | |
| `$count` | Supported | Inline count (`true`/`false`) |
| `$search` | Not yet | Planned for a future release |
| `$compute` | Not yet | Planned for a future release |
| `$apply` | Not yet | Data aggregation extension, planned for a future release |
| `$format` | Not yet | |
| `$index` | Not yet | |
| `$schemaversion` | Not yet | |
| `$skiptoken` | Not yet | Opaque server-driven paging token |
| `$deltatoken` | Not yet | Opaque server-driven delta token |

### Filter Operators

| Category | Operators | Status |
|---|---|---|
| Comparison | `eq`, `ne`, `gt`, `ge`, `lt`, `le` | Supported |
| Logical | `and`, `or`, `not` | Supported |
| Arithmetic | `add`, `sub`, `mul`, `div`, `divby`, `mod` | Supported |
| Membership | `in` | Supported |
| Enum flags | `has` | Supported |
| Grouping | `( )` | Supported |
| Lambda | `any`, `all` | Supported |
| Negation | `-` (unary minus) | Supported |

### Filter Functions

| Category | Functions | Status |
|---|---|---|
| String | `contains`, `startswith`, `endswith`, `length`, `indexof`, `substring`, `tolower`, `toupper`, `trim`, `concat`, `matchesPattern` | Supported |
| Date/Time | `year`, `month`, `day`, `hour`, `minute`, `second`, `fractionalseconds`, `totalseconds`, `date`, `time`, `totaloffsetminutes`, `now`, `mindatetime`, `maxdatetime` | Supported |
| Math | `round`, `floor`, `ceiling` | Supported |
| Geo | `geo.distance`, `geo.length`, `geo.intersects` | Supported |
| Collection | `hassubset`, `hassubsequence` | Supported |
| Type | `cast`, `isof` | Supported |

### Literal Types

| Type | Example | Status |
|---|---|---|
| Null | `null` | Supported |
| Boolean | `true`, `false` | Supported |
| Integer | `42`, `-1` | Supported |
| Decimal | `3.14`, `1.5e10` | Supported |
| String | `'Milk'`, `'O''Brien'` | Supported |
| GUID | `01234567-89ab-cdef-0123-456789abcdef` | Supported |
| Date | `2023-01-15` | Supported |
| DateTimeOffset | `2023-01-15T14:30:00Z` | Supported |
| TimeOfDay | `14:30:00` | Supported |
| Duration | `duration'P1DT2H30M'` | Supported |
| NaN / Infinity | `NaN`, `INF`, `-INF` | Supported |
| Binary | `binary'T0RhdGE='` | Not yet |
| Enum | `Namespace.Color'Red'` | Not yet |
| Geography/Geometry | `geography'SRID=0;Point(...)'` | Not yet |

## Design Decisions

- **Framework-agnostic** -- zero dependencies, works with any PHP 8.2+ project. Use it with Laravel, Symfony, API Platform, Slim, or plain PHP.
- **Schema-unaware** -- the parser does not validate property names or types against a data model. It parses syntax only. Schema validation belongs in a separate layer.
- **Immutable AST** -- all nodes are `readonly class`, safe to cache and share.
- **Pratt parser** -- the `$filter` expression parser uses top-down operator precedence parsing for correct handling of all 11 precedence levels defined in the OData 4.01 spec.

## Requirements

- PHP >= 8.2

## License

MIT
