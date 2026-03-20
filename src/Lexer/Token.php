<?php

declare(strict_types=1);

namespace NovaBytes\OData\Lexer;

readonly class Token
{
    public function __construct(
        public TokenType $type,
        public string $value,
        public int $position,
    ) {}
}
