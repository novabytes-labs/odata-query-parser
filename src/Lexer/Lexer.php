<?php

declare(strict_types=1);

namespace NovaBytes\OData\Lexer;

use NovaBytes\OData\Exception\ParseException;

class Lexer
{
    private string $input;
    private int $pos;
    private int $length;

    /** @var list<Token> */
    private array $tokens = [];
    private int $cursor = 0;

    private const KEYWORDS = [
        'eq' => TokenType::Eq,
        'ne' => TokenType::Ne,
        'gt' => TokenType::Gt,
        'ge' => TokenType::Ge,
        'lt' => TokenType::Lt,
        'le' => TokenType::Le,
        'in' => TokenType::In,
        'has' => TokenType::Has,
        'and' => TokenType::And,
        'or' => TokenType::Or,
        'not' => TokenType::Not,
        'add' => TokenType::Add,
        'sub' => TokenType::Sub,
        'mul' => TokenType::Mul,
        'div' => TokenType::Div,
        'divby' => TokenType::DivBy,
        'mod' => TokenType::Mod,
        'asc' => TokenType::Asc,
        'desc' => TokenType::Desc,
        'true' => TokenType::True,
        'false' => TokenType::False,
        'null' => TokenType::Null,
        'any' => TokenType::Any,
        'all' => TokenType::All,
    ];

    /**
     * Tokenize the given OData expression string.
     */
    public function __construct(string $input)
    {
        $this->input = $input;
        $this->pos = 0;
        $this->length = strlen($input);
        $this->scan();
    }

    /**
     * Return the token at the current cursor position.
     */
    public function current(): Token
    {
        return $this->tokens[$this->cursor] ?? new Token(TokenType::Eof, '', $this->length);
    }

    /**
     * Return the token at the given offset from the current cursor position.
     */
    public function peek(int $offset = 1): Token
    {
        return $this->tokens[$this->cursor + $offset] ?? new Token(TokenType::Eof, '', $this->length);
    }

    /**
     * Return the current token and advance the cursor.
     */
    public function advance(): Token
    {
        $token = $this->current();
        $this->cursor++;
        return $token;
    }

    /**
     * Assert the current token is of the expected type, consume it, or throw.
     */
    public function expect(TokenType $type): Token
    {
        $token = $this->current();
        if ($token->type !== $type) {
            throw ParseException::unexpectedToken(
                $type->value,
                $token->value ?: 'end of input',
                $token->position,
            );
        }
        $this->cursor++;
        return $token;
    }

    /**
     * Check whether the current token matches the given type.
     */
    public function is(TokenType $type): bool
    {
        return $this->current()->type === $type;
    }

    /**
     * Check whether the cursor has reached end of input.
     */
    public function isEof(): bool
    {
        return $this->is(TokenType::Eof);
    }

    /**
     * Consume the current token if it matches the given type, otherwise return null.
     */
    public function optionalConsume(TokenType $type): ?Token
    {
        if ($this->is($type)) {
            return $this->advance();
        }
        return null;
    }

    /** @return list<Token> */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * Return the current cursor position in the token list.
     */
    public function getPosition(): int
    {
        return $this->cursor;
    }

    /**
     * Restore the cursor to a previously saved position.
     */
    public function setPosition(int $position): void
    {
        $this->cursor = $position;
    }

    /**
     * Scan the entire input string and populate the token list.
     */
    private function scan(): void
    {
        while ($this->pos < $this->length) {
            $this->skipWhitespace();

            if ($this->pos >= $this->length) {
                break;
            }

            $char = $this->input[$this->pos];

            $token = match ($char) {
                '(' => $this->single(TokenType::OpenParen),
                ')' => $this->single(TokenType::CloseParen),
                ',' => $this->single(TokenType::Comma),
                '/' => $this->single(TokenType::Slash),
                ':' => $this->single(TokenType::Colon),
                ';' => $this->single(TokenType::Semicolon),
                '*' => $this->single(TokenType::Star),
                '@' => $this->single(TokenType::At),
                '=' => $this->single(TokenType::Equals),
                '-' => $this->readMinusOrNegativeNumber(),
                '\'' => $this->readString(),
                default => null,
            };

            if ($token !== null) {
                $this->tokens[] = $token;
                continue;
            }

            if ($this->isDigit($char)) {
                $this->tokens[] = $this->readNumber();
                continue;
            }

            if ($this->isIdentifierStart($char)) {
                $this->tokens[] = $this->readIdentifierOrKeyword();
                continue;
            }

            // $ prefix for system query options (e.g. $filter, $select inside nested options)
            if ($char === '$' && $this->pos + 1 < $this->length && $this->isIdentifierStart($this->input[$this->pos + 1])) {
                $this->pos++; // skip '$'
                $token = $this->readIdentifierOrKeyword();
                // Prefix the value with '$' to indicate system query option
                $this->tokens[] = new Token($token->type, '$' . $token->value, $token->position - 1);
                continue;
            }

            throw ParseException::unexpectedCharacter($char, $this->pos);
        }

        $this->tokens[] = new Token(TokenType::Eof, '', $this->length);
    }

    /**
     * Create a single-character token and advance the position.
     */
    private function single(TokenType $type): Token
    {
        $token = new Token($type, $this->input[$this->pos], $this->pos);
        $this->pos++;
        return $token;
    }

    /**
     * Skip over whitespace characters and percent-encoded whitespace (%20, %09).
     */
    private function skipWhitespace(): void
    {
        while ($this->pos < $this->length) {
            $char = $this->input[$this->pos];
            if ($char === ' ' || $char === "\t") {
                $this->pos++;
            } elseif ($char === '%' && $this->pos + 2 < $this->length) {
                $encoded = substr($this->input, $this->pos, 3);
                if ($encoded === '%20' || $encoded === '%09') {
                    $this->pos += 3;
                } else {
                    break;
                }
            } else {
                break;
            }
        }
    }

    /**
     * Read a single-quoted string literal, handling escaped quotes ('').
     */
    private function readString(): Token
    {
        $start = $this->pos;
        $this->pos++; // skip opening quote
        $value = '';

        while ($this->pos < $this->length) {
            $char = $this->input[$this->pos];

            if ($char === '\'') {
                // Check for escaped quote ('')
                if ($this->pos + 1 < $this->length && $this->input[$this->pos + 1] === '\'') {
                    $value .= '\'';
                    $this->pos += 2;
                } else {
                    $this->pos++; // skip closing quote
                    return new Token(TokenType::String, $value, $start);
                }
            } else {
                $value .= $char;
                $this->pos++;
            }
        }

        throw new ParseException("Unterminated string literal starting at position {$start}", $start);
    }

    /**
     * Read a numeric literal (integer, decimal, or scientific notation), or delegate to GUID/date readers.
     */
    private function readNumber(): Token
    {
        $start = $this->pos;
        $value = '';
        $isDecimal = false;

        while ($this->pos < $this->length && $this->isDigit($this->input[$this->pos])) {
            $value .= $this->input[$this->pos];
            $this->pos++;
        }

        // Check for GUID pattern: 8HEXDIG-4HEXDIG-4HEXDIG-4HEXDIG-12HEXDIG
        if ($this->pos < $this->length && $this->input[$this->pos] === '-' && $this->looksLikeGuid($start)) {
            return $this->readGuid($start);
        }

        // Check for date pattern: YYYY-MM-DD
        if ($this->pos < $this->length && $this->input[$this->pos] === '-' && strlen($value) === 4) {
            return $this->readDateOrDateTimeOffset($start, $value);
        }

        if ($this->pos < $this->length && $this->input[$this->pos] === '.') {
            $isDecimal = true;
            $value .= '.';
            $this->pos++;

            while ($this->pos < $this->length && $this->isDigit($this->input[$this->pos])) {
                $value .= $this->input[$this->pos];
                $this->pos++;
            }
        }

        // Scientific notation
        if ($this->pos < $this->length && ($this->input[$this->pos] === 'e' || $this->input[$this->pos] === 'E')) {
            $isDecimal = true;
            $value .= $this->input[$this->pos];
            $this->pos++;

            if ($this->pos < $this->length && ($this->input[$this->pos] === '+' || $this->input[$this->pos] === '-')) {
                $value .= $this->input[$this->pos];
                $this->pos++;
            }

            while ($this->pos < $this->length && $this->isDigit($this->input[$this->pos])) {
                $value .= $this->input[$this->pos];
                $this->pos++;
            }
        }

        return new Token(
            $isDecimal ? TokenType::Decimal : TokenType::Integer,
            $value,
            $start,
        );
    }

    /**
     * Read a minus operator, a negative number, or the -INF literal.
     */
    private function readMinusOrNegativeNumber(): Token
    {
        $start = $this->pos;

        // Check if next char is a digit — if so, it's a negative number
        if ($this->pos + 1 < $this->length && $this->isDigit($this->input[$this->pos + 1])) {
            $this->pos++; // skip '-'
            $numberToken = $this->readNumber();
            return new Token(
                $numberToken->type,
                '-' . $numberToken->value,
                $start,
            );
        }

        // Check for -INF
        if ($this->pos + 3 < $this->length && substr($this->input, $this->pos, 4) === '-INF') {
            $this->pos += 4;
            return new Token(TokenType::Decimal, '-INF', $start);
        }

        return $this->single(TokenType::Minus);
    }

    /**
     * Read an identifier or keyword token, including special literals (NaN, INF, duration, time-of-day).
     */
    private function readIdentifierOrKeyword(): Token
    {
        $start = $this->pos;
        $value = '';

        while ($this->pos < $this->length && $this->isIdentifierChar($this->input[$this->pos])) {
            $value .= $this->input[$this->pos];
            $this->pos++;
        }

        // Check for special literal values
        if ($value === 'NaN' || $value === 'INF') {
            return new Token(TokenType::Decimal, $value, $start);
        }

        // Check for duration literal: duration'...'
        if ($value === 'duration' && $this->pos < $this->length && $this->input[$this->pos] === '\'') {
            return $this->readDuration($start);
        }

        // Check for time-of-day pattern: HH:MM...
        if (strlen($value) === 2 && ctype_digit($value) && $this->pos < $this->length && $this->input[$this->pos] === ':') {
            return $this->readTimeOfDay($start, $value);
        }

        // Keywords must be followed by whitespace, '(', ')', ',', or be at the end
        // to prevent matching 'android' as 'and' + 'roid'
        if (isset(self::KEYWORDS[$value]) && $this->isKeywordBoundary()) {
            return new Token(self::KEYWORDS[$value], $value, $start);
        }

        return new Token(TokenType::Identifier, $value, $start);
    }

    /**
     * Read a duration literal enclosed in single quotes (e.g. duration'P1DT2H').
     */
    private function readDuration(int $start): Token
    {
        $this->pos++; // skip opening quote
        $value = '';

        while ($this->pos < $this->length && $this->input[$this->pos] !== '\'') {
            $value .= $this->input[$this->pos];
            $this->pos++;
        }

        if ($this->pos >= $this->length) {
            throw new ParseException("Unterminated duration literal starting at position {$start}", $start);
        }

        $this->pos++; // skip closing quote

        return new Token(TokenType::Duration, $value, $start);
    }

    /**
     * Read a time-of-day literal (HH:MM[:SS[.fractional]]).
     */
    private function readTimeOfDay(int $start, string $hours): Token
    {
        $value = $hours;
        // Consume the ':' and minutes
        $value .= $this->input[$this->pos]; // ':'
        $this->pos++;

        // Read minutes
        while ($this->pos < $this->length && $this->isDigit($this->input[$this->pos])) {
            $value .= $this->input[$this->pos];
            $this->pos++;
        }

        // Optional seconds
        if ($this->pos < $this->length && $this->input[$this->pos] === ':') {
            $value .= $this->input[$this->pos];
            $this->pos++;

            while ($this->pos < $this->length && ($this->isDigit($this->input[$this->pos]) || $this->input[$this->pos] === '.')) {
                $value .= $this->input[$this->pos];
                $this->pos++;
            }
        }

        return new Token(TokenType::TimeOfDay, $value, $start);
    }

    /**
     * Read a date or DateTimeOffset literal starting from a four-digit year.
     */
    private function readDateOrDateTimeOffset(int $start, string $year): Token
    {
        $value = $year;

        // Read -MM-DD
        $value .= $this->input[$this->pos]; // '-'
        $this->pos++;

        while ($this->pos < $this->length && $this->isDigit($this->input[$this->pos])) {
            $value .= $this->input[$this->pos];
            $this->pos++;
        }

        if ($this->pos < $this->length && $this->input[$this->pos] === '-') {
            $value .= $this->input[$this->pos];
            $this->pos++;

            while ($this->pos < $this->length && $this->isDigit($this->input[$this->pos])) {
                $value .= $this->input[$this->pos];
                $this->pos++;
            }
        }

        // Check for 'T' indicating DateTimeOffset
        if ($this->pos < $this->length && $this->input[$this->pos] === 'T') {
            $value .= $this->input[$this->pos];
            $this->pos++;

            // Read time part: HH:MM[:SS[.fractional]]
            while ($this->pos < $this->length && ($this->isDigit($this->input[$this->pos]) || $this->input[$this->pos] === ':' || $this->input[$this->pos] === '.')) {
                $value .= $this->input[$this->pos];
                $this->pos++;
            }

            // Read timezone: Z or +/-HH:MM
            if ($this->pos < $this->length && ($this->input[$this->pos] === 'Z' || $this->input[$this->pos] === '+' || $this->input[$this->pos] === '-')) {
                $value .= $this->input[$this->pos];
                $this->pos++;

                // Read remaining timezone digits and ':'
                while ($this->pos < $this->length && ($this->isDigit($this->input[$this->pos]) || $this->input[$this->pos] === ':')) {
                    $value .= $this->input[$this->pos];
                    $this->pos++;
                }
            }

            return new Token(TokenType::DateTimeOffset, $value, $start);
        }

        return new Token(TokenType::Date, $value, $start);
    }

    /**
     * Check whether the remaining input from the given position matches a GUID pattern.
     */
    private function looksLikeGuid(int $start): bool
    {
        // GUID: 8HEXDIG-4HEXDIG-4HEXDIG-4HEXDIG-12HEXDIG
        $remaining = substr($this->input, $start);
        return (bool) preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/', $remaining);
    }

    /**
     * Read a GUID token in the 8-4-4-4-12 hex format.
     */
    private function readGuid(int $start): Token
    {
        $this->pos = $start;
        $value = '';

        // Read exactly 8-4-4-4-12 hex digits with dashes
        $pattern = [8, 4, 4, 4, 12];
        foreach ($pattern as $i => $count) {
            if ($i > 0) {
                $value .= $this->input[$this->pos]; // dash
                $this->pos++;
            }
            for ($j = 0; $j < $count; $j++) {
                $value .= $this->input[$this->pos];
                $this->pos++;
            }
        }

        return new Token(TokenType::Guid, $value, $start);
    }

    /**
     * Determine whether the given character is an ASCII digit.
     */
    private function isDigit(string $char): bool
    {
        return $char >= '0' && $char <= '9';
    }

    /**
     * Determine whether the given character can start an identifier (letter or underscore).
     */
    private function isIdentifierStart(string $char): bool
    {
        return ($char >= 'A' && $char <= 'Z')
            || ($char >= 'a' && $char <= 'z')
            || $char === '_';
    }

    /**
     * Determine whether the given character can appear in an identifier body.
     */
    private function isIdentifierChar(string $char): bool
    {
        return $this->isIdentifierStart($char) || $this->isDigit($char);
    }

    /**
     * Check whether the current position is a valid keyword boundary (not mid-identifier).
     */
    private function isKeywordBoundary(): bool
    {
        if ($this->pos >= $this->length) {
            return true;
        }

        $next = $this->input[$this->pos];
        return !$this->isIdentifierChar($next);
    }
}
