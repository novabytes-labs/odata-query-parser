<?php

declare(strict_types=1);

namespace NovaBytes\OData\Parser;

use NovaBytes\OData\AST\Expand\ExpandItem;
use NovaBytes\OData\Lexer\Lexer;
use NovaBytes\OData\Lexer\TokenType;

class ExpandParser
{
    /**
     * @return list<ExpandItem>
     */
    public static function parse(string $input): array
    {
        $lexer = new Lexer($input);
        $parser = new self($lexer);
        return $parser->parseExpandItems();
    }

    public function __construct(private Lexer $lexer) {}

    /**
     * @return list<ExpandItem>
     */
    public function parseExpandItems(): array
    {
        $items = [$this->parseExpandItem()];

        while ($this->lexer->is(TokenType::Comma)) {
            $this->lexer->advance();
            $items[] = $this->parseExpandItem();
        }

        return $items;
    }

    /**
     * Parse a single $expand item (wildcard, navigation path, or path with nested options).
     */
    private function parseExpandItem(): ExpandItem
    {
        // Wildcard
        if ($this->lexer->is(TokenType::Star)) {
            $this->lexer->advance();
            return new ExpandItem([], isWildcard: true);
        }

        // Navigation path
        $path = [$this->lexer->expect(TokenType::Identifier)->value];

        while ($this->lexer->is(TokenType::Slash)) {
            $this->lexer->advance();
            $path[] = $this->lexer->expect(TokenType::Identifier)->value;
        }

        // Nested options: Navigation($filter=...;$select=...;$expand=...)
        $nestedOptions = null;
        if ($this->lexer->is(TokenType::OpenParen)) {
            $nestedOptions = NestedOptionsParser::parse($this->lexer);
        }

        return new ExpandItem($path, nestedOptions: $nestedOptions);
    }
}
