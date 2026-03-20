<?php

declare(strict_types=1);

namespace NovaBytes\OData\Exception;

class ParseException extends \RuntimeException
{
    public function __construct(
        string $message,
        public readonly int $position = 0,
        public readonly ?string $expected = null,
        public readonly ?string $got = null,
    ) {
        parent::__construct($message);
    }

    public static function unexpectedToken(string $expected, string $got, int $position): self
    {
        return new self(
            "Unexpected '{$got}' at position {$position}; expected {$expected}",
            $position,
            $expected,
            $got,
        );
    }

    public static function unexpectedEnd(string $expected): self
    {
        return new self(
            "Unexpected end of input; expected {$expected}",
            expected: $expected,
        );
    }

    public static function unexpectedCharacter(string $char, int $position): self
    {
        return new self(
            "Unexpected character '{$char}' at position {$position}",
            $position,
            got: $char,
        );
    }
}
