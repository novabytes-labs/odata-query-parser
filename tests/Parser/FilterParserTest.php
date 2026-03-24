<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Parser;

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
use NovaBytes\OData\Exception\ParseException;
use NovaBytes\OData\Parser\FilterParser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FilterParserTest extends TestCase
{
    // ── Simple comparisons ──────────────────────────────────────────

    #[Test]
    public function it_parses_simple_eq(): void
    {
        $expr = FilterParser::parse("Name eq 'Milk'");

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::Eq, $expr->operator);
        $this->assertInstanceOf(PropertyPath::class, $expr->left);
        $this->assertSame(['Name'], $expr->left->segments);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame('Milk', $expr->right->value);
        $this->assertSame(LiteralType::String, $expr->right->type);
    }

    #[Test]
    public function it_parses_numeric_comparison(): void
    {
        $expr = FilterParser::parse('Price gt 100');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::Gt, $expr->operator);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame(100, $expr->right->value);
        $this->assertSame(LiteralType::Integer, $expr->right->type);
    }

    #[Test]
    public function it_parses_decimal_comparison(): void
    {
        $expr = FilterParser::parse('Price lt 9.99');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::Lt, $expr->operator);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame(9.99, $expr->right->value);
    }

    #[Test]
    public function it_parses_null_comparison(): void
    {
        $expr = FilterParser::parse('Name eq null');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertNull($expr->right->value);
        $this->assertSame(LiteralType::Null, $expr->right->type);
    }

    #[Test]
    public function it_parses_boolean_comparison(): void
    {
        $expr = FilterParser::parse('IsActive eq true');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertTrue($expr->right->value);
    }

    // ── All comparison operators ─────────────────────────────────────

    #[Test]
    public function it_parses_ne(): void
    {
        $expr = FilterParser::parse("Status ne 'Active'");
        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::Ne, $expr->operator);
    }

    #[Test]
    public function it_parses_ge(): void
    {
        $expr = FilterParser::parse('Price ge 10');
        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::Ge, $expr->operator);
    }

    #[Test]
    public function it_parses_le(): void
    {
        $expr = FilterParser::parse('Price le 10');
        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::Le, $expr->operator);
    }

    // ── Logical operators ────────────────────────────────────────────

    #[Test]
    public function it_parses_and(): void
    {
        $expr = FilterParser::parse('Price gt 5 and Price lt 10');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::And, $expr->operator);
        $this->assertInstanceOf(BinaryExpression::class, $expr->left);
        $this->assertInstanceOf(BinaryExpression::class, $expr->right);
    }

    #[Test]
    public function it_parses_or(): void
    {
        $expr = FilterParser::parse("Name eq 'Milk' or Name eq 'Cheese'");

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::Or, $expr->operator);
    }

    #[Test]
    public function it_handles_precedence_and_over_or(): void
    {
        // A or B and C should parse as A or (B and C)
        $expr = FilterParser::parse('A eq 1 or B eq 2 and C eq 3');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::Or, $expr->operator);

        // Right side should be "B eq 2 and C eq 3"
        $this->assertInstanceOf(BinaryExpression::class, $expr->right);
        $this->assertSame(BinaryOperator::And, $expr->right->operator);
    }

    #[Test]
    public function it_parses_not(): void
    {
        $expr = FilterParser::parse("not contains(Name,'milk')");

        $this->assertInstanceOf(UnaryExpression::class, $expr);
        $this->assertSame(UnaryOperator::Not, $expr->operator);
        $this->assertInstanceOf(FunctionCall::class, $expr->operand);
    }

    // ── Arithmetic operators ─────────────────────────────────────────

    #[Test]
    public function it_parses_arithmetic(): void
    {
        $expr = FilterParser::parse('Price add 5 gt 10');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::Gt, $expr->operator);

        $this->assertInstanceOf(BinaryExpression::class, $expr->left);
        $this->assertSame(BinaryOperator::Add, $expr->left->operator);
    }

    #[Test]
    public function it_handles_arithmetic_precedence(): void
    {
        // Price add Tax mul Rate → Price add (Tax mul Rate) because mul binds tighter
        $expr = FilterParser::parse('Price add Tax mul Rate gt 100');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::Gt, $expr->operator);

        $left = $expr->left;
        $this->assertInstanceOf(BinaryExpression::class, $left);
        $this->assertSame(BinaryOperator::Add, $left->operator);

        $this->assertInstanceOf(BinaryExpression::class, $left->right);
        $this->assertSame(BinaryOperator::Mul, $left->right->operator);
    }

    // ── Negation ─────────────────────────────────────────────────────

    #[Test]
    public function it_parses_negation(): void
    {
        $expr = FilterParser::parse('Price eq -1');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        // The -1 should be parsed as a negative integer literal
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame(-1, $expr->right->value);
    }

    // ── Parenthesized grouping ───────────────────────────────────────

    #[Test]
    public function it_handles_parentheses(): void
    {
        // (A or B) and C should parse with correct grouping
        $expr = FilterParser::parse('(A eq 1 or B eq 2) and C eq 3');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::And, $expr->operator);

        // Left side should be "A eq 1 or B eq 2"
        $this->assertInstanceOf(BinaryExpression::class, $expr->left);
        $this->assertSame(BinaryOperator::Or, $expr->left->operator);
    }

    // ── Property paths ───────────────────────────────────────────────

    #[Test]
    public function it_parses_nested_property_path(): void
    {
        $expr = FilterParser::parse("Address/City eq 'London'");

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(PropertyPath::class, $expr->left);
        $this->assertSame(['Address', 'City'], $expr->left->segments);
    }

    #[Test]
    public function it_parses_deeply_nested_path(): void
    {
        $expr = FilterParser::parse("Address/Country/Name eq 'UK'");

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(PropertyPath::class, $expr->left);
        $this->assertSame(['Address', 'Country', 'Name'], $expr->left->segments);
    }

    // ── Function calls ───────────────────────────────────────────────

    #[Test]
    public function it_parses_contains(): void
    {
        $expr = FilterParser::parse("contains(Name,'milk')");

        $this->assertInstanceOf(FunctionCall::class, $expr);
        $this->assertSame('contains', $expr->name);
        $this->assertCount(2, $expr->arguments);
        $this->assertInstanceOf(PropertyPath::class, $expr->arguments[0]);
        $this->assertInstanceOf(Literal::class, $expr->arguments[1]);
    }

    #[Test]
    public function it_parses_startswith(): void
    {
        $expr = FilterParser::parse("startswith(Name,'Che')");

        $this->assertInstanceOf(FunctionCall::class, $expr);
        $this->assertSame('startswith', $expr->name);
        $this->assertCount(2, $expr->arguments);
    }

    #[Test]
    public function it_parses_endswith(): void
    {
        $expr = FilterParser::parse("endswith(Name,'ilk')");

        $this->assertInstanceOf(FunctionCall::class, $expr);
        $this->assertSame('endswith', $expr->name);
    }

    #[Test]
    public function it_parses_length(): void
    {
        $expr = FilterParser::parse('length(Name) gt 5');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(FunctionCall::class, $expr->left);
        $this->assertSame('length', $expr->left->name);
    }

    #[Test]
    public function it_parses_tolower(): void
    {
        $expr = FilterParser::parse("tolower(Name) eq 'milk'");

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(FunctionCall::class, $expr->left);
        $this->assertSame('tolower', $expr->left->name);
    }

    #[Test]
    public function it_parses_substring(): void
    {
        $expr = FilterParser::parse("substring(Name,1,3) eq 'ilk'");

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(FunctionCall::class, $expr->left);
        $this->assertSame('substring', $expr->left->name);
        $this->assertCount(3, $expr->left->arguments);
    }

    #[Test]
    public function it_parses_concat(): void
    {
        $expr = FilterParser::parse("concat(FirstName,LastName) eq 'JohnDoe'");

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(FunctionCall::class, $expr->left);
        $this->assertSame('concat', $expr->left->name);
    }

    #[Test]
    public function it_parses_year_function(): void
    {
        $expr = FilterParser::parse('year(BirthDate) eq 1990');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(FunctionCall::class, $expr->left);
        $this->assertSame('year', $expr->left->name);
    }

    #[Test]
    public function it_parses_now_function(): void
    {
        $expr = FilterParser::parse('Date gt now()');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(FunctionCall::class, $expr->right);
        $this->assertSame('now', $expr->right->name);
        $this->assertCount(0, $expr->right->arguments);
    }

    #[Test]
    public function it_parses_round_function(): void
    {
        $expr = FilterParser::parse('round(Price) eq 10');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(FunctionCall::class, $expr->left);
        $this->assertSame('round', $expr->left->name);
    }

    // ── Lambda expressions ───────────────────────────────────────────

    #[Test]
    public function it_parses_any_lambda(): void
    {
        $expr = FilterParser::parse('Items/any(d:d/Qty gt 100)');

        $this->assertInstanceOf(LambdaExpression::class, $expr);
        $this->assertSame(LambdaOperator::Any, $expr->operator);
        $this->assertSame('d', $expr->variable);

        $this->assertInstanceOf(PropertyPath::class, $expr->collection);
        $this->assertSame(['Items'], $expr->collection->segments);

        $this->assertInstanceOf(BinaryExpression::class, $expr->predicate);
    }

    #[Test]
    public function it_parses_any_without_predicate(): void
    {
        $expr = FilterParser::parse('Items/any()');

        $this->assertInstanceOf(LambdaExpression::class, $expr);
        $this->assertSame(LambdaOperator::Any, $expr->operator);
        $this->assertNull($expr->variable);
        $this->assertNull($expr->predicate);
    }

    #[Test]
    public function it_parses_all_lambda(): void
    {
        $expr = FilterParser::parse('Items/all(d:d/Qty gt 0)');

        $this->assertInstanceOf(LambdaExpression::class, $expr);
        $this->assertSame(LambdaOperator::All, $expr->operator);
        $this->assertSame('d', $expr->variable);
    }

    // ── In operator with list ────────────────────────────────────────

    #[Test]
    public function it_parses_in_operator(): void
    {
        $expr = FilterParser::parse("Name in ('Milk','Cheese','Butter')");

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::In, $expr->operator);
        $this->assertInstanceOf(ListExpression::class, $expr->right);
        $this->assertCount(3, $expr->right->items);
    }

    // ── Date and GUID literals ───────────────────────────────────────

    #[Test]
    public function it_parses_date_literal(): void
    {
        $expr = FilterParser::parse('BirthDate eq 2023-01-15');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame(LiteralType::Date, $expr->right->type);
        $this->assertSame('2023-01-15', $expr->right->value);
    }

    #[Test]
    public function it_parses_datetime_offset_literal(): void
    {
        $expr = FilterParser::parse('Created eq 2023-01-15T14:30:00Z');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame(LiteralType::DateTimeOffset, $expr->right->type);
    }

    #[Test]
    public function it_parses_guid_literal(): void
    {
        $expr = FilterParser::parse('Id eq 01234567-89ab-cdef-0123-456789abcdef');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame(LiteralType::Guid, $expr->right->type);
        $this->assertSame('01234567-89ab-cdef-0123-456789abcdef', $expr->right->value);
    }

    // ── Complex expressions ──────────────────────────────────────────

    #[Test]
    public function it_parses_complex_filter(): void
    {
        $expr = FilterParser::parse("Price gt 5 and (Name eq 'Milk' or Name eq 'Cheese') and contains(Description,'fresh')");

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::And, $expr->operator);
    }

    #[Test]
    public function it_parses_function_in_comparison(): void
    {
        $expr = FilterParser::parse("contains(Name,'milk') eq true");

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(FunctionCall::class, $expr->left);
    }

    // ── Error cases ──────────────────────────────────────────────────

    #[Test]
    public function it_throws_on_empty_input(): void
    {
        $this->expectException(ParseException::class);
        FilterParser::parse('');
    }

    #[Test]
    public function it_throws_on_unexpected_token(): void
    {
        $this->expectException(ParseException::class);
        FilterParser::parse('eq eq eq');
    }

    #[Test]
    public function it_throws_on_trailing_tokens(): void
    {
        $this->expectException(ParseException::class);
        FilterParser::parse('Name eq 1 2');
    }

    // ── Additional comparison operators ─────────────────────────────

    #[Test]
    public function it_parses_has_operator(): void
    {
        $expr = FilterParser::parse("Style has 'Yellow'");

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::Has, $expr->operator);
    }

    // ── Additional arithmetic operators ─────────────────────────────

    #[Test]
    public function it_parses_sub_operator(): void
    {
        $expr = FilterParser::parse('Price sub 5 gt 0');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertSame(BinaryOperator::Gt, $expr->operator);
        $this->assertInstanceOf(BinaryExpression::class, $expr->left);
        $this->assertSame(BinaryOperator::Sub, $expr->left->operator);
    }

    #[Test]
    public function it_parses_div_operator(): void
    {
        $expr = FilterParser::parse('Price div 2 eq 5');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(BinaryExpression::class, $expr->left);
        $this->assertSame(BinaryOperator::Div, $expr->left->operator);
    }

    #[Test]
    public function it_parses_divby_operator(): void
    {
        $expr = FilterParser::parse('Price divby 2 gt 5');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(BinaryExpression::class, $expr->left);
        $this->assertSame(BinaryOperator::DivBy, $expr->left->operator);
    }

    #[Test]
    public function it_parses_mod_operator(): void
    {
        $expr = FilterParser::parse('Price mod 2 eq 0');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(BinaryExpression::class, $expr->left);
        $this->assertSame(BinaryOperator::Mod, $expr->left->operator);
    }

    // ── Additional literal types ────────────────────────────────────

    #[Test]
    public function it_parses_duration_literal(): void
    {
        $expr = FilterParser::parse("Duration eq duration'P1DT2H30M'");

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame(LiteralType::Duration, $expr->right->type);
        $this->assertSame('P1DT2H30M', $expr->right->value);
    }

    #[Test]
    public function it_parses_negative_decimal(): void
    {
        $expr = FilterParser::parse('Price eq -3.14');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame(-3.14, $expr->right->value);
        $this->assertSame(LiteralType::Decimal, $expr->right->type);
    }

    #[Test]
    public function it_parses_scientific_notation_in_filter(): void
    {
        $expr = FilterParser::parse('Value eq 1.5e10');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame(LiteralType::Decimal, $expr->right->type);
        $this->assertSame(1.5e10, $expr->right->value);
    }

    // ── Additional functions ────────────────────────────────────────

    #[Test]
    public function it_parses_matchesPattern_function(): void
    {
        $expr = FilterParser::parse("matchesPattern(Name,'^A')");

        $this->assertInstanceOf(FunctionCall::class, $expr);
        $this->assertSame('matchesPattern', $expr->name);
        $this->assertCount(2, $expr->arguments);
    }

    #[Test]
    public function it_parses_nested_function_calls(): void
    {
        $expr = FilterParser::parse("contains(tolower(Name),'milk')");

        $this->assertInstanceOf(FunctionCall::class, $expr);
        $this->assertSame('contains', $expr->name);
        $this->assertCount(2, $expr->arguments);
        $this->assertInstanceOf(FunctionCall::class, $expr->arguments[0]);
        $this->assertSame('tolower', $expr->arguments[0]->name);
    }

    #[Test]
    public function it_parses_boolean_false_literal(): void
    {
        $expr = FilterParser::parse('IsActive eq false');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertFalse($expr->right->value);
        $this->assertSame(LiteralType::Boolean, $expr->right->type);
    }

    // ── Unary minus on expression ───────────────────────────────────

    #[Test]
    public function it_parses_unary_minus_on_expression(): void
    {
        $expr = FilterParser::parse('-Price gt 0');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(UnaryExpression::class, $expr->left);
        $this->assertSame(UnaryOperator::Negate, $expr->left->operator);
    }

    // ── Special decimal values (NaN, INF, -INF) ────────────────────────

    #[Test]
    public function it_parses_nan_literal(): void
    {
        $expr = FilterParser::parse('Value eq NaN');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame(LiteralType::Decimal, $expr->right->type);
        $this->assertNan($expr->right->value);
    }

    #[Test]
    public function it_parses_inf_literal(): void
    {
        $expr = FilterParser::parse('Value eq INF');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame(LiteralType::Decimal, $expr->right->type);
        $this->assertSame(INF, $expr->right->value);
    }

    #[Test]
    public function it_parses_negative_inf_literal(): void
    {
        $expr = FilterParser::parse('Value eq -INF');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(Literal::class, $expr->right);
        $this->assertSame(LiteralType::Decimal, $expr->right->type);
        $this->assertSame(-INF, $expr->right->value);
    }

    // ── Path-based function calls ───────────────────────────────────────

    #[Test]
    public function it_parses_function_call_on_property_path(): void
    {
        $expr = FilterParser::parse('Items/length() gt 0');

        $this->assertInstanceOf(BinaryExpression::class, $expr);
        $this->assertInstanceOf(FunctionCall::class, $expr->left);
        $this->assertSame('length', $expr->left->name);
        $this->assertCount(1, $expr->left->arguments);
        $this->assertInstanceOf(PropertyPath::class, $expr->left->arguments[0]);
        $this->assertSame(['Items'], $expr->left->arguments[0]->segments);
    }

    #[Test]
    public function it_parses_function_call_on_property_path_with_args(): void
    {
        $expr = FilterParser::parse("Items/contains(Name,'test')");

        $this->assertInstanceOf(FunctionCall::class, $expr);
        $this->assertSame('contains', $expr->name);
        $this->assertCount(3, $expr->arguments);
        $this->assertInstanceOf(PropertyPath::class, $expr->arguments[0]);
        $this->assertSame(['Items'], $expr->arguments[0]->segments);
    }

    // ── Unexpected token after slash ────────────────────────────────────

    #[Test]
    public function it_throws_on_unexpected_token_after_slash(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('identifier');
        FilterParser::parse('Items/123');
    }
}
