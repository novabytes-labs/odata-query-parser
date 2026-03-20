<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Exception;

use NovaBytes\OData\Exception\ParseException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ParseExceptionTest extends TestCase
{
    #[Test]
    public function it_creates_unexpected_end_exception(): void
    {
        $exception = ParseException::unexpectedEnd('expression');

        $this->assertStringContainsString('Unexpected end of input', $exception->getMessage());
        $this->assertSame('expression', $exception->expected);
        $this->assertNull($exception->got);
    }

    #[Test]
    public function it_creates_unexpected_token_exception(): void
    {
        $exception = ParseException::unexpectedToken('identifier', 'eq', 5);

        $this->assertStringContainsString("Unexpected 'eq'", $exception->getMessage());
        $this->assertSame(5, $exception->position);
        $this->assertSame('identifier', $exception->expected);
        $this->assertSame('eq', $exception->got);
    }

    #[Test]
    public function it_creates_unexpected_character_exception(): void
    {
        $exception = ParseException::unexpectedCharacter('#', 10);

        $this->assertStringContainsString("Unexpected character '#'", $exception->getMessage());
        $this->assertSame(10, $exception->position);
        $this->assertSame('#', $exception->got);
    }

    #[Test]
    public function it_constructs_with_default_values(): void
    {
        $exception = new ParseException('Test error');

        $this->assertSame('Test error', $exception->getMessage());
        $this->assertSame(0, $exception->position);
        $this->assertNull($exception->expected);
        $this->assertNull($exception->got);
    }
}
