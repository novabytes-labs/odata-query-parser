<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Visitor;

use NovaBytes\OData\AST\Expression;
use NovaBytes\OData\AST\Filter\BinaryExpression;
use NovaBytes\OData\AST\Filter\BinaryOperator;
use NovaBytes\OData\AST\Filter\FunctionCall;
use NovaBytes\OData\AST\Filter\LambdaExpression;
use NovaBytes\OData\AST\Filter\LambdaOperator;
use NovaBytes\OData\AST\Filter\ListExpression;
use NovaBytes\OData\AST\Filter\Literal;
use NovaBytes\OData\AST\Filter\LiteralType;
use NovaBytes\OData\AST\Filter\PropertyPath;
use NovaBytes\OData\AST\Filter\UnaryExpression;
use NovaBytes\OData\AST\Filter\UnaryOperator;
use NovaBytes\OData\Parser\FilterParser;
use NovaBytes\OData\Visitor\StringifyVisitor;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class StringifyVisitorTest extends TestCase
{
    private StringifyVisitor $visitor;

    protected function setUp(): void
    {
        $this->visitor = new StringifyVisitor();
    }

    // ── Binary expressions ──────────────────────────────────────────

    #[Test]
    public function it_stringifies_simple_binary_expression(): void
    {
        $expr = new BinaryExpression(
            new PropertyPath(['Price']),
            BinaryOperator::Gt,
            new Literal(100, LiteralType::Integer),
        );

        $this->assertSame('Price gt 100', $this->visitor->stringify($expr));
    }

    // ── Literals ────────────────────────────────────────────────────

    #[Test]
    public function it_stringifies_null_literal(): void
    {
        $this->assertSame('null', $this->visitor->stringify(new Literal(null, LiteralType::Null)));
    }

    #[Test]
    public function it_stringifies_boolean_true_literal(): void
    {
        $this->assertSame('true', $this->visitor->stringify(new Literal(true, LiteralType::Boolean)));
    }

    #[Test]
    public function it_stringifies_boolean_false_literal(): void
    {
        $this->assertSame('false', $this->visitor->stringify(new Literal(false, LiteralType::Boolean)));
    }

    #[Test]
    public function it_stringifies_integer_literal(): void
    {
        $this->assertSame('42', $this->visitor->stringify(new Literal(42, LiteralType::Integer)));
    }

    #[Test]
    public function it_stringifies_decimal_literal(): void
    {
        $this->assertSame('3.14', $this->visitor->stringify(new Literal(3.14, LiteralType::Decimal)));
    }

    #[Test]
    public function it_stringifies_string_literal_with_escaped_quotes(): void
    {
        $this->assertSame("'O''Brien'", $this->visitor->stringify(new Literal("O'Brien", LiteralType::String)));
    }

    #[Test]
    public function it_stringifies_string_literal(): void
    {
        $this->assertSame("'Milk'", $this->visitor->stringify(new Literal('Milk', LiteralType::String)));
    }

    #[Test]
    public function it_stringifies_guid_literal(): void
    {
        $guid = '01234567-89ab-cdef-0123-456789abcdef';
        $this->assertSame($guid, $this->visitor->stringify(new Literal($guid, LiteralType::Guid)));
    }

    #[Test]
    public function it_stringifies_date_literal(): void
    {
        $this->assertSame('2023-01-15', $this->visitor->stringify(new Literal('2023-01-15', LiteralType::Date)));
    }

    #[Test]
    public function it_stringifies_datetimeoffset_literal(): void
    {
        $value = '2023-01-15T14:30:00Z';
        $this->assertSame($value, $this->visitor->stringify(new Literal($value, LiteralType::DateTimeOffset)));
    }

    #[Test]
    public function it_stringifies_timeofday_literal(): void
    {
        $this->assertSame('14:30:00', $this->visitor->stringify(new Literal('14:30:00', LiteralType::TimeOfDay)));
    }

    #[Test]
    public function it_stringifies_duration_literal(): void
    {
        $this->assertSame("duration'P1DT2H30M'", $this->visitor->stringify(new Literal('P1DT2H30M', LiteralType::Duration)));
    }

    // ── Special decimal values ──────────────────────────────────────

    #[Test]
    public function it_stringifies_nan_literal(): void
    {
        $this->assertSame('NaN', $this->visitor->stringify(new Literal(NAN, LiteralType::Decimal)));
    }

    #[Test]
    public function it_stringifies_inf_literal(): void
    {
        $this->assertSame('INF', $this->visitor->stringify(new Literal(INF, LiteralType::Decimal)));
    }

    #[Test]
    public function it_stringifies_negative_inf_literal(): void
    {
        $this->assertSame('-INF', $this->visitor->stringify(new Literal(-INF, LiteralType::Decimal)));
    }

    // ── Unary expressions ───────────────────────────────────────────

    #[Test]
    public function it_stringifies_not_expression(): void
    {
        $expr = new UnaryExpression(
            UnaryOperator::Not,
            new Literal(true, LiteralType::Boolean),
        );

        $this->assertSame('not true', $this->visitor->stringify($expr));
    }

    #[Test]
    public function it_stringifies_negate_expression(): void
    {
        $expr = new UnaryExpression(
            UnaryOperator::Negate,
            new PropertyPath(['Price']),
        );

        $this->assertSame('-Price', $this->visitor->stringify($expr));
    }

    // ── Property path ───────────────────────────────────────────────

    #[Test]
    public function it_stringifies_property_path(): void
    {
        $this->assertSame('Address/City', $this->visitor->stringify(new PropertyPath(['Address', 'City'])));
    }

    // ── Function calls ──────────────────────────────────────────────

    #[Test]
    public function it_stringifies_function_call(): void
    {
        $expr = new FunctionCall('contains', [
            new PropertyPath(['Name']),
            new Literal('milk', LiteralType::String),
        ]);

        $this->assertSame("contains(Name,'milk')", $this->visitor->stringify($expr));
    }

    #[Test]
    public function it_stringifies_function_call_with_no_args(): void
    {
        $expr = new FunctionCall('now', []);
        $this->assertSame('now()', $this->visitor->stringify($expr));
    }

    // ── Lambda expressions ──────────────────────────────────────────

    #[Test]
    public function it_stringifies_lambda_with_predicate(): void
    {
        $expr = new LambdaExpression(
            new PropertyPath(['Items']),
            LambdaOperator::Any,
            'd',
            new BinaryExpression(
                new PropertyPath(['d', 'Qty']),
                BinaryOperator::Gt,
                new Literal(100, LiteralType::Integer),
            ),
        );

        $this->assertSame('Items/any(d:d/Qty gt 100)', $this->visitor->stringify($expr));
    }

    #[Test]
    public function it_stringifies_lambda_without_predicate(): void
    {
        $expr = new LambdaExpression(
            new PropertyPath(['Items']),
            LambdaOperator::Any,
            null,
            null,
        );

        $this->assertSame('Items/any()', $this->visitor->stringify($expr));
    }

    // ── List expression ─────────────────────────────────────────────

    #[Test]
    public function it_stringifies_list_expression(): void
    {
        $expr = new ListExpression([
            new Literal(1, LiteralType::Integer),
            new Literal(2, LiteralType::Integer),
            new Literal(3, LiteralType::Integer),
        ]);

        $this->assertSame('(1,2,3)', $this->visitor->stringify($expr));
    }

    // ── Error case ──────────────────────────────────────────────────

    #[Test]
    public function it_throws_on_unknown_expression_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $mock = new class implements Expression {};
        $this->visitor->stringify($mock);
    }

    // ── Roundtrip ───────────────────────────────────────────────────

    #[Test]
    public function it_roundtrips_complex_expression(): void
    {
        $input = "Price gt 5 and contains(Name,'milk')";
        $parsed = FilterParser::parse($input);
        $stringified = $this->visitor->stringify($parsed);

        // Parse the stringified version and stringify again to confirm stability
        $reparsed = FilterParser::parse($stringified);
        $restringified = $this->visitor->stringify($reparsed);

        $this->assertSame($stringified, $restringified);
    }
}
