<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Lexer;

use NovaBytes\OData\Exception\ParseException;
use NovaBytes\OData\Lexer\Lexer;
use NovaBytes\OData\Lexer\TokenType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
    #[Test]
    public function it_tokenizes_simple_comparison(): void
    {
        $lexer = new Lexer('Price gt 100');

        $this->assertSame(TokenType::Identifier, $lexer->current()->type);
        $this->assertSame('Price', $lexer->current()->value);

        $lexer->advance();
        $this->assertSame(TokenType::Gt, $lexer->current()->type);

        $lexer->advance();
        $this->assertSame(TokenType::Integer, $lexer->current()->type);
        $this->assertSame('100', $lexer->current()->value);

        $lexer->advance();
        $this->assertTrue($lexer->isEof());
    }

    #[Test]
    public function it_handles_string_literals(): void
    {
        $lexer = new Lexer("Name eq 'Milk'");

        $lexer->advance(); // Name
        $lexer->advance(); // eq
        $this->assertSame(TokenType::String, $lexer->current()->type);
        $this->assertSame('Milk', $lexer->current()->value);
    }

    #[Test]
    public function it_handles_escaped_quotes_in_strings(): void
    {
        $lexer = new Lexer("Name eq 'O''Brien'");

        $lexer->advance(); // Name
        $lexer->advance(); // eq
        $this->assertSame(TokenType::String, $lexer->current()->type);
        $this->assertSame("O'Brien", $lexer->current()->value);
    }

    #[Test]
    public function it_handles_keyword_boundary_correctly(): void
    {
        // 'android' should NOT be split into 'and' + 'roid'
        $lexer = new Lexer('android eq true');

        $this->assertSame(TokenType::Identifier, $lexer->current()->type);
        $this->assertSame('android', $lexer->current()->value);
    }

    #[Test]
    public function it_handles_origin_as_identifier(): void
    {
        // 'origin' should NOT be split into 'or' + 'igin'
        $lexer = new Lexer('origin eq true');

        $this->assertSame(TokenType::Identifier, $lexer->current()->type);
        $this->assertSame('origin', $lexer->current()->value);
    }

    #[Test]
    public function it_handles_noticeable_as_identifier(): void
    {
        // 'noticeable' should NOT be 'not' + 'iceable'
        $lexer = new Lexer('noticeable eq true');

        $this->assertSame(TokenType::Identifier, $lexer->current()->type);
        $this->assertSame('noticeable', $lexer->current()->value);
    }

    #[Test]
    public function it_tokenizes_decimal_values(): void
    {
        $lexer = new Lexer('3.14');

        $this->assertSame(TokenType::Decimal, $lexer->current()->type);
        $this->assertSame('3.14', $lexer->current()->value);
    }

    #[Test]
    public function it_tokenizes_negative_numbers(): void
    {
        $lexer = new Lexer('-42');

        $this->assertSame(TokenType::Integer, $lexer->current()->type);
        $this->assertSame('-42', $lexer->current()->value);
    }

    #[Test]
    public function it_tokenizes_guid_values(): void
    {
        $lexer = new Lexer('01234567-89ab-cdef-0123-456789abcdef');

        $this->assertSame(TokenType::Guid, $lexer->current()->type);
        $this->assertSame('01234567-89ab-cdef-0123-456789abcdef', $lexer->current()->value);
    }

    #[Test]
    public function it_tokenizes_date_values(): void
    {
        $lexer = new Lexer('2023-01-15');

        $this->assertSame(TokenType::Date, $lexer->current()->type);
        $this->assertSame('2023-01-15', $lexer->current()->value);
    }

    #[Test]
    public function it_tokenizes_datetimeoffset_values(): void
    {
        $lexer = new Lexer('2023-01-15T14:30:00Z');

        $this->assertSame(TokenType::DateTimeOffset, $lexer->current()->type);
        $this->assertSame('2023-01-15T14:30:00Z', $lexer->current()->value);
    }

    #[Test]
    public function it_tokenizes_duration_values(): void
    {
        $lexer = new Lexer("duration'P1DT2H30M'");

        $this->assertSame(TokenType::Duration, $lexer->current()->type);
        $this->assertSame('P1DT2H30M', $lexer->current()->value);
    }

    #[Test]
    public function it_tokenizes_nan_and_inf(): void
    {
        $lexer = new Lexer('NaN');
        $this->assertSame(TokenType::Decimal, $lexer->current()->type);
        $this->assertSame('NaN', $lexer->current()->value);

        $lexer = new Lexer('INF');
        $this->assertSame(TokenType::Decimal, $lexer->current()->type);
        $this->assertSame('INF', $lexer->current()->value);

        $lexer = new Lexer('-INF');
        $this->assertSame(TokenType::Decimal, $lexer->current()->type);
        $this->assertSame('-INF', $lexer->current()->value);
    }

    #[Test]
    public function it_tokenizes_parentheses_and_commas(): void
    {
        $lexer = new Lexer('contains(Name,X)');

        $tokens = $lexer->getTokens();
        $types = array_map(fn($t) => $t->type, $tokens);

        $this->assertSame([
            TokenType::Identifier, // contains
            TokenType::OpenParen,
            TokenType::Identifier, // Name
            TokenType::Comma,
            TokenType::Identifier, // X
            TokenType::CloseParen,
            TokenType::Eof,
        ], $types);
    }

    #[Test]
    public function it_tokenizes_dollar_prefixed_identifiers(): void
    {
        $lexer = new Lexer('$filter=Price');

        $this->assertSame(TokenType::Identifier, $lexer->current()->type);
        $this->assertSame('$filter', $lexer->current()->value);

        $lexer->advance();
        $this->assertSame(TokenType::Equals, $lexer->current()->type);
    }

    #[Test]
    public function it_throws_on_unterminated_string(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('Unterminated string');
        new Lexer("'unterminated");
    }

    #[Test]
    public function it_throws_on_unexpected_character(): void
    {
        $this->expectException(ParseException::class);
        new Lexer('Price # 100');
    }

    #[Test]
    public function it_tokenizes_lambda_syntax(): void
    {
        $lexer = new Lexer('Items/any(d:d/Qty gt 100)');

        $tokens = $lexer->getTokens();
        $types = array_map(fn($t) => $t->type, $tokens);

        $this->assertSame([
            TokenType::Identifier, // Items
            TokenType::Slash,
            TokenType::Any,
            TokenType::OpenParen,
            TokenType::Identifier, // d
            TokenType::Colon,
            TokenType::Identifier, // d
            TokenType::Slash,
            TokenType::Identifier, // Qty
            TokenType::Gt,
            TokenType::Integer,    // 100
            TokenType::CloseParen,
            TokenType::Eof,
        ], $types);
    }

    #[Test]
    public function it_handles_scientific_notation(): void
    {
        $lexer = new Lexer('1.5e10');
        $this->assertSame(TokenType::Decimal, $lexer->current()->type);
        $this->assertSame('1.5e10', $lexer->current()->value);
    }

    #[Test]
    public function it_records_token_positions(): void
    {
        $lexer = new Lexer('A eq 1');

        $this->assertSame(0, $lexer->current()->position);
        $lexer->advance();
        $this->assertSame(2, $lexer->current()->position);
        $lexer->advance();
        $this->assertSame(5, $lexer->current()->position);
    }

    // ── peek() ──────────────────────────────────────────────────────

    #[Test]
    public function it_peeks_ahead_without_advancing(): void
    {
        $lexer = new Lexer('A eq 1');

        $peeked = $lexer->peek();
        $this->assertSame(TokenType::Eq, $peeked->type);
        // Current should still be 'A'
        $this->assertSame(TokenType::Identifier, $lexer->current()->type);
        $this->assertSame('A', $lexer->current()->value);
    }

    #[Test]
    public function it_peeks_beyond_token_list_returns_eof(): void
    {
        $lexer = new Lexer('A');

        $peeked = $lexer->peek(999);
        $this->assertSame(TokenType::Eof, $peeked->type);
    }

    // ── optionalConsume() ───────────────────────────────────────────

    #[Test]
    public function it_optionally_consumes_matching_token(): void
    {
        $lexer = new Lexer('A eq 1');

        $token = $lexer->optionalConsume(TokenType::Identifier);
        $this->assertNotNull($token);
        $this->assertSame('A', $token->value);
        // Cursor should have advanced to 'eq'
        $this->assertSame(TokenType::Eq, $lexer->current()->type);
    }

    #[Test]
    public function it_optionally_consumes_returns_null_on_mismatch(): void
    {
        $lexer = new Lexer('A eq 1');

        $token = $lexer->optionalConsume(TokenType::String);
        $this->assertNull($token);
        // Cursor should not have advanced
        $this->assertSame(TokenType::Identifier, $lexer->current()->type);
    }

    // ── getPosition() / setPosition() ───────────────────────────────

    #[Test]
    public function it_saves_and_restores_position(): void
    {
        $lexer = new Lexer('A eq 1');

        $saved = $lexer->getPosition();
        $this->assertSame(0, $saved);

        $lexer->advance(); // move to 'eq'
        $lexer->advance(); // move to '1'
        $this->assertSame(TokenType::Integer, $lexer->current()->type);

        $lexer->setPosition($saved);
        $this->assertSame(TokenType::Identifier, $lexer->current()->type);
        $this->assertSame('A', $lexer->current()->value);
    }

    // ── Percent-encoded whitespace ──────────────────────────────────

    #[Test]
    public function it_handles_percent_encoded_whitespace(): void
    {
        $lexer = new Lexer('Price%20gt%20100');

        $this->assertSame(TokenType::Identifier, $lexer->current()->type);
        $this->assertSame('Price', $lexer->current()->value);

        $lexer->advance();
        $this->assertSame(TokenType::Gt, $lexer->current()->type);

        $lexer->advance();
        $this->assertSame(TokenType::Integer, $lexer->current()->type);
        $this->assertSame('100', $lexer->current()->value);
    }

    #[Test]
    public function it_handles_percent_encoded_tab(): void
    {
        $lexer = new Lexer('A%09eq%091');

        $this->assertSame(TokenType::Identifier, $lexer->current()->type);
        $this->assertSame('A', $lexer->current()->value);

        $lexer->advance();
        $this->assertSame(TokenType::Eq, $lexer->current()->type);

        $lexer->advance();
        $this->assertSame(TokenType::Integer, $lexer->current()->type);
    }

    // ── Unterminated duration ───────────────────────────────────────

    #[Test]
    public function it_throws_on_unterminated_duration(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('Unterminated duration');
        new Lexer("duration'P1DT2H");
    }

    // ── Scientific notation with sign ───────────────────────────────

    #[Test]
    public function it_tokenizes_scientific_notation_with_positive_sign(): void
    {
        $lexer = new Lexer('1.5e+10');

        $this->assertSame(TokenType::Decimal, $lexer->current()->type);
        $this->assertSame('1.5e+10', $lexer->current()->value);
    }

    #[Test]
    public function it_tokenizes_scientific_notation_with_negative_sign(): void
    {
        $lexer = new Lexer('1.5e-10');

        $this->assertSame(TokenType::Decimal, $lexer->current()->type);
        $this->assertSame('1.5e-10', $lexer->current()->value);
    }

    // ── Minus as operator ───────────────────────────────────────────

    #[Test]
    public function it_tokenizes_minus_as_operator(): void
    {
        $lexer = new Lexer('A - B');

        $lexer->advance(); // skip A
        $this->assertSame(TokenType::Minus, $lexer->current()->type);
        $this->assertSame('-', $lexer->current()->value);
    }

    // ── expect() error path ───────────────────────────────────────────

    #[Test]
    public function it_throws_on_expect_type_mismatch(): void
    {
        $lexer = new Lexer('A eq 1');

        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('expected INTEGER');
        $lexer->expect(TokenType::Integer);
    }

    #[Test]
    public function it_throws_on_expect_at_eof(): void
    {
        $lexer = new Lexer('A');

        $lexer->advance(); // consume A, now at EOF
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('end of input');
        $lexer->expect(TokenType::Identifier);
    }

    // ── Trailing whitespace (scan break path) ─────────────────────────

    #[Test]
    public function it_handles_trailing_whitespace(): void
    {
        $lexer = new Lexer('A  ');

        $this->assertSame(TokenType::Identifier, $lexer->current()->type);
        $this->assertSame('A', $lexer->current()->value);
        $lexer->advance();
        $this->assertTrue($lexer->isEof());
    }

    // ── @ token ───────────────────────────────────────────────────────

    #[Test]
    public function it_tokenizes_at_sign(): void
    {
        $lexer = new Lexer('@param');

        $this->assertSame(TokenType::At, $lexer->current()->type);
        $this->assertSame('@', $lexer->current()->value);
        $lexer->advance();
        $this->assertSame(TokenType::Identifier, $lexer->current()->type);
        $this->assertSame('param', $lexer->current()->value);
    }

    // ── Non-whitespace percent-encoded sequence ───────────────────────

    #[Test]
    public function it_stops_skipping_whitespace_at_non_whitespace_percent_encoded(): void
    {
        // %28 is '(' percent-encoded — skipWhitespace encounters '%' but it's
        // not %20 or %09, so it hits the break. Then '%' is unexpected char.
        $this->expectException(ParseException::class);
        new Lexer('A %28');
    }

    // ── DateTimeOffset with timezone offset ───────────────────────────

    #[Test]
    public function it_tokenizes_datetimeoffset_with_positive_offset(): void
    {
        $lexer = new Lexer('2023-01-15T14:30:00+05:30');

        $this->assertSame(TokenType::DateTimeOffset, $lexer->current()->type);
        $this->assertSame('2023-01-15T14:30:00+05:30', $lexer->current()->value);
    }

    #[Test]
    public function it_tokenizes_datetimeoffset_with_negative_offset(): void
    {
        $lexer = new Lexer('2023-01-15T14:30:00-08:00');

        $this->assertSame(TokenType::DateTimeOffset, $lexer->current()->type);
        $this->assertSame('2023-01-15T14:30:00-08:00', $lexer->current()->value);
    }
}
