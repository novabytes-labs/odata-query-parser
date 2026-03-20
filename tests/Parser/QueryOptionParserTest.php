<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Parser;

use NovaBytes\OData\AST\Filter\BinaryExpression;
use NovaBytes\OData\AST\Filter\BinaryOperator;
use NovaBytes\OData\AST\Filter\PropertyPath;
use NovaBytes\OData\AST\OrderBy\SortDirection;
use NovaBytes\OData\Exception\ParseException;
use NovaBytes\OData\Parser\QueryOptionParser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class QueryOptionParserTest extends TestCase
{
    // ── $filter ──────────────────────────────────────────────────────

    #[Test]
    public function it_parses_filter(): void
    {
        $result = QueryOptionParser::parse('$filter=Price gt 100');

        $this->assertNotNull($result->filter);
        $this->assertInstanceOf(BinaryExpression::class, $result->filter);
        $this->assertSame(BinaryOperator::Gt, $result->filter->operator);
    }

    #[Test]
    public function it_parses_filter_without_dollar_prefix(): void
    {
        $result = QueryOptionParser::parse('filter=Price gt 100');

        $this->assertNotNull($result->filter);
        $this->assertInstanceOf(BinaryExpression::class, $result->filter);
    }

    // ── $select ──────────────────────────────────────────────────────

    #[Test]
    public function it_parses_select(): void
    {
        $result = QueryOptionParser::parse('$select=Name,Price,Description');

        $this->assertNotNull($result->select);
        $this->assertCount(3, $result->select);
        $this->assertSame(['Name'], $result->select[0]->path);
        $this->assertSame(['Price'], $result->select[1]->path);
        $this->assertSame(['Description'], $result->select[2]->path);
    }

    #[Test]
    public function it_parses_select_wildcard(): void
    {
        $result = QueryOptionParser::parse('$select=*');

        $this->assertNotNull($result->select);
        $this->assertCount(1, $result->select);
        $this->assertTrue($result->select[0]->isWildcard);
    }

    #[Test]
    public function it_parses_select_with_nested_path(): void
    {
        $result = QueryOptionParser::parse('$select=Address/City,Address/Country');

        $this->assertNotNull($result->select);
        $this->assertCount(2, $result->select);
        $this->assertSame(['Address', 'City'], $result->select[0]->path);
        $this->assertSame(['Address', 'Country'], $result->select[1]->path);
    }

    // ── $expand ──────────────────────────────────────────────────────

    #[Test]
    public function it_parses_expand(): void
    {
        $result = QueryOptionParser::parse('$expand=Products,Category');

        $this->assertNotNull($result->expand);
        $this->assertCount(2, $result->expand);
        $this->assertSame(['Products'], $result->expand[0]->path);
        $this->assertSame(['Category'], $result->expand[1]->path);
    }

    #[Test]
    public function it_parses_expand_with_nested_options(): void
    {
        $result = QueryOptionParser::parse('$expand=Products($filter=Price gt 100;$top=5;$select=Name)');

        $this->assertNotNull($result->expand);
        $this->assertCount(1, $result->expand);
        $this->assertSame(['Products'], $result->expand[0]->path);

        $nested = $result->expand[0]->nestedOptions;
        $this->assertNotNull($nested);
        $this->assertNotNull($nested->filter);
        $this->assertSame(5, $nested->top);
        $this->assertNotNull($nested->select);
        $this->assertCount(1, $nested->select);
    }

    #[Test]
    public function it_parses_expand_wildcard(): void
    {
        $result = QueryOptionParser::parse('$expand=*');

        $this->assertNotNull($result->expand);
        $this->assertCount(1, $result->expand);
        $this->assertTrue($result->expand[0]->isWildcard);
    }

    // ── $orderby ─────────────────────────────────────────────────────

    #[Test]
    public function it_parses_orderby(): void
    {
        $result = QueryOptionParser::parse('$orderby=Name asc,Price desc');

        $this->assertNotNull($result->orderby);
        $this->assertCount(2, $result->orderby);

        $this->assertInstanceOf(PropertyPath::class, $result->orderby[0]->expression);
        $this->assertSame(SortDirection::Asc, $result->orderby[0]->direction);

        $this->assertInstanceOf(PropertyPath::class, $result->orderby[1]->expression);
        $this->assertSame(SortDirection::Desc, $result->orderby[1]->direction);
    }

    #[Test]
    public function it_defaults_orderby_to_asc(): void
    {
        $result = QueryOptionParser::parse('$orderby=Name');

        $this->assertNotNull($result->orderby);
        $this->assertSame(SortDirection::Asc, $result->orderby[0]->direction);
    }

    // ── $top and $skip ───────────────────────────────────────────────

    #[Test]
    public function it_parses_top(): void
    {
        $result = QueryOptionParser::parse('$top=10');
        $this->assertSame(10, $result->top);
    }

    #[Test]
    public function it_parses_skip(): void
    {
        $result = QueryOptionParser::parse('$skip=20');
        $this->assertSame(20, $result->skip);
    }

    // ── $count ───────────────────────────────────────────────────────

    #[Test]
    public function it_parses_count_true(): void
    {
        $result = QueryOptionParser::parse('$count=true');
        $this->assertTrue($result->count);
    }

    #[Test]
    public function it_parses_count_false(): void
    {
        $result = QueryOptionParser::parse('$count=false');
        $this->assertFalse($result->count);
    }

    // ── Combined query options ───────────────────────────────────────

    #[Test]
    public function it_parses_multiple_options(): void
    {
        $result = QueryOptionParser::parse('$filter=Price gt 5&$select=Name,Price&$orderby=Name asc&$top=10&$skip=20&$count=true');

        $this->assertNotNull($result->filter);
        $this->assertNotNull($result->select);
        $this->assertCount(2, $result->select);
        $this->assertNotNull($result->orderby);
        $this->assertSame(10, $result->top);
        $this->assertSame(20, $result->skip);
        $this->assertTrue($result->count);
    }

    #[Test]
    public function it_ignores_unknown_options(): void
    {
        $result = QueryOptionParser::parse('$filter=Price gt 5&customOption=value');

        $this->assertNotNull($result->filter);
        // No exception thrown for unknown options
    }

    #[Test]
    public function it_handles_empty_query_string(): void
    {
        $result = QueryOptionParser::parse('');

        $this->assertNull($result->filter);
        $this->assertNull($result->select);
        $this->assertNull($result->expand);
        $this->assertNull($result->orderby);
        $this->assertNull($result->top);
        $this->assertNull($result->skip);
        $this->assertNull($result->count);
    }

    // ── Error cases ──────────────────────────────────────────────────

    #[Test]
    public function it_throws_on_invalid_top(): void
    {
        $this->expectException(ParseException::class);
        QueryOptionParser::parse('$top=abc');
    }

    #[Test]
    public function it_throws_on_invalid_count(): void
    {
        $this->expectException(ParseException::class);
        QueryOptionParser::parse('$count=yes');
    }

    // ── Complex real-world queries ───────────────────────────────────

    #[Test]
    public function it_throws_on_invalid_skip(): void
    {
        $this->expectException(ParseException::class);
        QueryOptionParser::parse('$skip=abc');
    }

    #[Test]
    public function it_decodes_percent_encoded_filter(): void
    {
        // %20 is decoded to space; %27 is preserved (single quote handled by lexer)
        $result = QueryOptionParser::parse('$filter=Price%20gt%20100');

        $this->assertNotNull($result->filter);
        $this->assertInstanceOf(BinaryExpression::class, $result->filter);
    }

    #[Test]
    public function it_handles_pair_without_equals_sign(): void
    {
        // A pair without '=' should not throw, just be treated as key with empty value
        $result = QueryOptionParser::parse('orphanedKey');

        // Unknown options are silently ignored
        $this->assertNull($result->filter);
    }

    #[Test]
    public function it_parses_realistic_query(): void
    {
        $query = '$filter=contains(Name,\'Widget\') and Price gt 5.00'
            . '&$select=Name,Price,Category/Name'
            . '&$expand=Category($select=Name,Description)'
            . '&$orderby=Price desc'
            . '&$top=50'
            . '&$skip=100'
            . '&$count=true';

        $result = QueryOptionParser::parse($query);

        $this->assertNotNull($result->filter);
        $this->assertInstanceOf(BinaryExpression::class, $result->filter);

        $this->assertCount(3, $result->select);
        $this->assertSame(['Category', 'Name'], $result->select[2]->path);

        $this->assertCount(1, $result->expand);
        $this->assertNotNull($result->expand[0]->nestedOptions);
        $this->assertCount(2, $result->expand[0]->nestedOptions->select);

        $this->assertCount(1, $result->orderby);
        $this->assertSame(SortDirection::Desc, $result->orderby[0]->direction);

        $this->assertSame(50, $result->top);
        $this->assertSame(100, $result->skip);
        $this->assertTrue($result->count);
    }
}
