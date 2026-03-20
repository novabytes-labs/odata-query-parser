<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Parser;

use NovaBytes\OData\Parser\ExpandParser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExpandParserTest extends TestCase
{
    #[Test]
    public function it_parses_single_navigation(): void
    {
        $items = ExpandParser::parse('Products');

        $this->assertCount(1, $items);
        $this->assertSame(['Products'], $items[0]->path);
        $this->assertFalse($items[0]->isWildcard);
    }

    #[Test]
    public function it_parses_multiple_navigations(): void
    {
        $items = ExpandParser::parse('Products,Category');

        $this->assertCount(2, $items);
        $this->assertSame(['Products'], $items[0]->path);
        $this->assertSame(['Category'], $items[1]->path);
    }

    #[Test]
    public function it_parses_wildcard(): void
    {
        $items = ExpandParser::parse('*');

        $this->assertCount(1, $items);
        $this->assertTrue($items[0]->isWildcard);
    }

    #[Test]
    public function it_parses_nested_path(): void
    {
        $items = ExpandParser::parse('Products/Supplier');

        $this->assertCount(1, $items);
        $this->assertSame(['Products', 'Supplier'], $items[0]->path);
    }

    #[Test]
    public function it_parses_with_nested_options(): void
    {
        $items = ExpandParser::parse('Products($filter=Price gt 100;$top=5)');

        $this->assertCount(1, $items);
        $this->assertSame(['Products'], $items[0]->path);
        $this->assertNotNull($items[0]->nestedOptions);
        $this->assertNotNull($items[0]->nestedOptions->filter);
        $this->assertSame(5, $items[0]->nestedOptions->top);
    }

    #[Test]
    public function it_parses_with_nested_expand(): void
    {
        $items = ExpandParser::parse('Products($expand=Supplier)');

        $this->assertCount(1, $items);
        $this->assertNotNull($items[0]->nestedOptions);
        $this->assertNotNull($items[0]->nestedOptions->expand);
        $this->assertCount(1, $items[0]->nestedOptions->expand);
        $this->assertSame(['Supplier'], $items[0]->nestedOptions->expand[0]->path);
    }
}
