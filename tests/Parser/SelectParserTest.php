<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Parser;

use NovaBytes\OData\Parser\SelectParser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SelectParserTest extends TestCase
{
    #[Test]
    public function it_parses_single_property(): void
    {
        $items = SelectParser::parse('Name');

        $this->assertCount(1, $items);
        $this->assertSame(['Name'], $items[0]->path);
        $this->assertFalse($items[0]->isWildcard);
    }

    #[Test]
    public function it_parses_multiple_properties(): void
    {
        $items = SelectParser::parse('Name,Price,Description');

        $this->assertCount(3, $items);
        $this->assertSame(['Name'], $items[0]->path);
        $this->assertSame(['Price'], $items[1]->path);
        $this->assertSame(['Description'], $items[2]->path);
    }

    #[Test]
    public function it_parses_wildcard(): void
    {
        $items = SelectParser::parse('*');

        $this->assertCount(1, $items);
        $this->assertTrue($items[0]->isWildcard);
    }

    #[Test]
    public function it_parses_nested_path(): void
    {
        $items = SelectParser::parse('Address/City');

        $this->assertCount(1, $items);
        $this->assertSame(['Address', 'City'], $items[0]->path);
    }

    #[Test]
    public function it_parses_namespace_star_wildcard(): void
    {
        $items = SelectParser::parse('Namespace/*');

        $this->assertCount(1, $items);
        $this->assertSame(['Namespace', '*'], $items[0]->path);
        $this->assertFalse($items[0]->isWildcard);
    }

    #[Test]
    public function it_parses_select_with_nested_options(): void
    {
        $items = SelectParser::parse('Products($filter=Price gt 5;$top=10)');

        $this->assertCount(1, $items);
        $this->assertSame(['Products'], $items[0]->path);
        $this->assertNotNull($items[0]->nestedOptions);
        $this->assertNotNull($items[0]->nestedOptions->filter);
        $this->assertSame(10, $items[0]->nestedOptions->top);
    }
}
