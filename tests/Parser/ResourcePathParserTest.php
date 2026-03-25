<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Parser;

use NovaBytes\OData\Exception\ParseException;
use NovaBytes\OData\Parser\ResourcePathParser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ResourcePathParserTest extends TestCase
{
    #[Test]
    public function it_parses_entity_set_only(): void
    {
        $result = ResourcePathParser::parse('/Products');

        $this->assertSame('Products', $result->entitySet);
        $this->assertNull($result->key);
        $this->assertSame([], $result->navigationSegments);
        $this->assertFalse($result->isSingleEntity());
        $this->assertFalse($result->hasNavigation());
    }

    #[Test]
    public function it_parses_entity_set_without_leading_slash(): void
    {
        $result = ResourcePathParser::parse('Products');

        $this->assertSame('Products', $result->entitySet);
        $this->assertNull($result->key);
    }

    #[Test]
    public function it_parses_entity_set_with_trailing_slash(): void
    {
        $result = ResourcePathParser::parse('/Products/');

        $this->assertSame('Products', $result->entitySet);
        $this->assertNull($result->key);
    }

    #[Test]
    public function it_parses_integer_key(): void
    {
        $result = ResourcePathParser::parse('/Products(1)');

        $this->assertSame('Products', $result->entitySet);
        $this->assertNotNull($result->key);
        $this->assertTrue($result->key->isSingle());
        $this->assertSame(1, $result->key->getSingleValue());
        $this->assertTrue($result->isSingleEntity());
    }

    #[Test]
    public function it_parses_negative_integer_key(): void
    {
        $result = ResourcePathParser::parse('/Products(-5)');

        $this->assertSame(-5, $result->key->getSingleValue());
    }

    #[Test]
    public function it_parses_string_key(): void
    {
        $result = ResourcePathParser::parse("/Products('abc')");

        $this->assertSame('abc', $result->key->getSingleValue());
    }

    #[Test]
    public function it_parses_string_key_with_escaped_quotes(): void
    {
        $result = ResourcePathParser::parse("/Products('O''Brien')");

        $this->assertSame("O'Brien", $result->key->getSingleValue());
    }

    #[Test]
    public function it_parses_guid_key(): void
    {
        $result = ResourcePathParser::parse('/Products(01234567-89ab-cdef-0123-456789abcdef)');

        $this->assertSame('01234567-89ab-cdef-0123-456789abcdef', $result->key->getSingleValue());
    }

    #[Test]
    public function it_parses_composite_key(): void
    {
        $result = ResourcePathParser::parse('/OrderItems(OrderId=1,ItemId=2)');

        $this->assertNotNull($result->key);
        $this->assertFalse($result->key->isSingle());
        $this->assertNull($result->key->getSingleValue());
        $this->assertSame(['OrderId' => 1, 'ItemId' => 2], $result->key->values);
    }

    #[Test]
    public function it_parses_composite_key_with_string_value(): void
    {
        $result = ResourcePathParser::parse("/Items(Key1=1,Key2='abc')");

        $this->assertSame(['Key1' => 1, 'Key2' => 'abc'], $result->key->values);
    }

    #[Test]
    public function it_parses_navigation_segment(): void
    {
        $result = ResourcePathParser::parse('/Products(1)/Category');

        $this->assertSame('Products', $result->entitySet);
        $this->assertSame(1, $result->key->getSingleValue());
        $this->assertTrue($result->hasNavigation());
        $this->assertCount(1, $result->navigationSegments);
        $this->assertSame('Category', $result->navigationSegments[0]->property);
        $this->assertNull($result->navigationSegments[0]->key);
    }

    #[Test]
    public function it_parses_navigation_segment_with_key(): void
    {
        $result = ResourcePathParser::parse('/Products(1)/Reviews(5)');

        $this->assertCount(1, $result->navigationSegments);
        $this->assertSame('Reviews', $result->navigationSegments[0]->property);
        $this->assertSame(5, $result->navigationSegments[0]->key->getSingleValue());
    }

    #[Test]
    public function it_parses_multiple_navigation_segments(): void
    {
        $result = ResourcePathParser::parse('/Products(1)/Category/Parent');

        $this->assertCount(2, $result->navigationSegments);
        $this->assertSame('Category', $result->navigationSegments[0]->property);
        $this->assertSame('Parent', $result->navigationSegments[1]->property);
    }

    #[Test]
    public function it_parses_decimal_key(): void
    {
        $result = ResourcePathParser::parse('/Products(3.14)');

        $this->assertSame(3.14, $result->key->getSingleValue());
    }

    #[Test]
    public function it_parses_boolean_true_key(): void
    {
        $result = ResourcePathParser::parse('/Flags(true)');

        $this->assertTrue($result->key->getSingleValue());
    }

    #[Test]
    public function it_parses_boolean_false_key(): void
    {
        $result = ResourcePathParser::parse('/Flags(false)');

        $this->assertFalse($result->key->getSingleValue());
    }

    #[Test]
    public function it_parses_null_key(): void
    {
        $result = ResourcePathParser::parse('/Items(null)');

        $this->assertNull($result->key->getSingleValue());
    }

    #[Test]
    public function it_throws_on_empty_path(): void
    {
        $this->expectException(ParseException::class);

        ResourcePathParser::parse('');
    }

    #[Test]
    public function it_throws_on_slash_only_path(): void
    {
        $this->expectException(ParseException::class);

        ResourcePathParser::parse('/');
    }

    #[Test]
    public function it_throws_on_empty_key_expression(): void
    {
        $this->expectException(ParseException::class);

        ResourcePathParser::parse('/Products()');
    }

    #[Test]
    public function it_throws_on_invalid_identifier(): void
    {
        $this->expectException(ParseException::class);

        ResourcePathParser::parse('/123Products');
    }

    #[Test]
    public function it_throws_on_unterminated_key(): void
    {
        $this->expectException(ParseException::class);

        ResourcePathParser::parse('/Products(1');
    }

    #[Test]
    public function it_throws_on_invalid_key_literal(): void
    {
        $this->expectException(ParseException::class);

        ResourcePathParser::parse('/Products(invalid!)');
    }

    #[Test]
    public function it_throws_on_invalid_composite_key_format(): void
    {
        $this->expectException(ParseException::class);

        ResourcePathParser::parse('/Items(=1)');
    }

    #[Test]
    public function it_parses_entity_set_with_underscores(): void
    {
        $result = ResourcePathParser::parse('/Product_Items');

        $this->assertSame('Product_Items', $result->entitySet);
    }
}
