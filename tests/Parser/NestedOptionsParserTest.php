<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Parser;

use NovaBytes\OData\AST\Filter\BinaryExpression;
use NovaBytes\OData\AST\OrderBy\SortDirection;
use NovaBytes\OData\Exception\ParseException;
use NovaBytes\OData\Lexer\Lexer;
use NovaBytes\OData\Parser\NestedOptionsParser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class NestedOptionsParserTest extends TestCase
{
    #[Test]
    public function it_parses_nested_filter(): void
    {
        $lexer = new Lexer('($filter=Price gt 100)');
        $result = NestedOptionsParser::parse($lexer);

        $this->assertNotNull($result->filter);
        $this->assertInstanceOf(BinaryExpression::class, $result->filter);
    }

    #[Test]
    public function it_parses_nested_select(): void
    {
        $lexer = new Lexer('($select=Name,Price)');
        $result = NestedOptionsParser::parse($lexer);

        $this->assertNotNull($result->select);
        $this->assertCount(2, $result->select);
    }

    #[Test]
    public function it_parses_nested_orderby(): void
    {
        $lexer = new Lexer('($orderby=Name asc)');
        $result = NestedOptionsParser::parse($lexer);

        $this->assertNotNull($result->orderby);
        $this->assertCount(1, $result->orderby);
        $this->assertSame(SortDirection::Asc, $result->orderby[0]->direction);
    }

    #[Test]
    public function it_parses_nested_top(): void
    {
        $lexer = new Lexer('($top=10)');
        $result = NestedOptionsParser::parse($lexer);

        $this->assertSame(10, $result->top);
    }

    #[Test]
    public function it_parses_nested_skip(): void
    {
        $lexer = new Lexer('($skip=20)');
        $result = NestedOptionsParser::parse($lexer);

        $this->assertSame(20, $result->skip);
    }

    #[Test]
    public function it_parses_nested_count_true(): void
    {
        $lexer = new Lexer('($count=true)');
        $result = NestedOptionsParser::parse($lexer);

        $this->assertTrue($result->count);
    }

    #[Test]
    public function it_parses_nested_count_false(): void
    {
        $lexer = new Lexer('($count=false)');
        $result = NestedOptionsParser::parse($lexer);

        $this->assertFalse($result->count);
    }

    #[Test]
    public function it_parses_nested_expand(): void
    {
        $lexer = new Lexer('($expand=Supplier)');
        $result = NestedOptionsParser::parse($lexer);

        $this->assertNotNull($result->expand);
        $this->assertCount(1, $result->expand);
        $this->assertSame(['Supplier'], $result->expand[0]->path);
    }

    #[Test]
    public function it_parses_multiple_nested_options(): void
    {
        $lexer = new Lexer('($filter=Price gt 100;$orderby=Name asc;$top=5;$skip=10;$count=true)');
        $result = NestedOptionsParser::parse($lexer);

        $this->assertNotNull($result->filter);
        $this->assertNotNull($result->orderby);
        $this->assertSame(5, $result->top);
        $this->assertSame(10, $result->skip);
        $this->assertTrue($result->count);
    }

    #[Test]
    public function it_throws_on_invalid_count_value(): void
    {
        $this->expectException(ParseException::class);

        $lexer = new Lexer('($count=yes)');
        NestedOptionsParser::parse($lexer);
    }

    #[Test]
    public function it_throws_on_unknown_nested_option(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('Unknown nested query option');

        $lexer = new Lexer('($unknown=value)');
        NestedOptionsParser::parse($lexer);
    }

    #[Test]
    public function it_parses_option_name_without_dollar_prefix(): void
    {
        $lexer = new Lexer('(filter=Price gt 100)');
        $result = NestedOptionsParser::parse($lexer);

        $this->assertNotNull($result->filter);
        $this->assertInstanceOf(BinaryExpression::class, $result->filter);
    }

    #[Test]
    public function it_throws_on_non_identifier_option_name(): void
    {
        $this->expectException(ParseException::class);

        $lexer = new Lexer('(123=value)');
        NestedOptionsParser::parse($lexer);
    }
}
