<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Parser;

use NovaBytes\OData\AST\Filter\FunctionCall;
use NovaBytes\OData\AST\Filter\PropertyPath;
use NovaBytes\OData\AST\OrderBy\SortDirection;
use NovaBytes\OData\Parser\OrderByParser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class OrderByParserTest extends TestCase
{
    #[Test]
    public function it_parses_single_property_asc(): void
    {
        $items = OrderByParser::parse('Name asc');

        $this->assertCount(1, $items);
        $this->assertInstanceOf(PropertyPath::class, $items[0]->expression);
        $this->assertSame(SortDirection::Asc, $items[0]->direction);
    }

    #[Test]
    public function it_parses_single_property_desc(): void
    {
        $items = OrderByParser::parse('Price desc');

        $this->assertCount(1, $items);
        $this->assertSame(SortDirection::Desc, $items[0]->direction);
    }

    #[Test]
    public function it_defaults_direction_to_asc(): void
    {
        $items = OrderByParser::parse('Name');

        $this->assertCount(1, $items);
        $this->assertSame(SortDirection::Asc, $items[0]->direction);
    }

    #[Test]
    public function it_parses_multiple_orderby_items(): void
    {
        $items = OrderByParser::parse('Name asc,Price desc');

        $this->assertCount(2, $items);
        $this->assertSame(SortDirection::Asc, $items[0]->direction);
        $this->assertSame(SortDirection::Desc, $items[1]->direction);
    }

    #[Test]
    public function it_parses_function_expression_in_orderby(): void
    {
        $items = OrderByParser::parse('length(Name) desc');

        $this->assertCount(1, $items);
        $this->assertInstanceOf(FunctionCall::class, $items[0]->expression);
        $this->assertSame('length', $items[0]->expression->name);
        $this->assertSame(SortDirection::Desc, $items[0]->direction);
    }

    #[Test]
    public function it_parses_nested_property_path_in_orderby(): void
    {
        $items = OrderByParser::parse('Address/City asc');

        $this->assertCount(1, $items);
        $this->assertInstanceOf(PropertyPath::class, $items[0]->expression);
        $this->assertSame(['Address', 'City'], $items[0]->expression->segments);
    }
}
