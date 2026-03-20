<?php

declare(strict_types=1);

namespace NovaBytes\OData\Parser;

use NovaBytes\OData\AST\Select\SelectItem;
use NovaBytes\OData\Lexer\Lexer;
use NovaBytes\OData\Lexer\TokenType;

class SelectParser
{
    /**
     * @return list<SelectItem>
     */
    public static function parse(string $input): array
    {
        $lexer = new Lexer($input);
        $parser = new self($lexer);
        return $parser->parseSelectItems();
    }

    public function __construct(private Lexer $lexer) {}

    /**
     * @return list<SelectItem>
     */
    public function parseSelectItems(): array
    {
        $items = [$this->parseSelectItem()];

        while ($this->lexer->is(TokenType::Comma)) {
            $this->lexer->advance();
            $items[] = $this->parseSelectItem();
        }

        return $items;
    }

    private function parseSelectItem(): SelectItem
    {
        // Wildcard
        if ($this->lexer->is(TokenType::Star)) {
            $this->lexer->advance();
            return new SelectItem([], isWildcard: true);
        }

        // Property path: segment/segment/...
        $path = [$this->lexer->expect(TokenType::Identifier)->value];

        while ($this->lexer->is(TokenType::Slash)) {
            $this->lexer->advance();
            if ($this->lexer->is(TokenType::Star)) {
                $this->lexer->advance();
                // namespace.* pattern — treat the path built so far + '*'
                $path[] = '*';
                return new SelectItem($path);
            }
            $path[] = $this->lexer->expect(TokenType::Identifier)->value;
        }

        // Check for nested options: property($select=...;$filter=...)
        $nestedOptions = null;
        if ($this->lexer->is(TokenType::OpenParen)) {
            $nestedOptions = NestedOptionsParser::parse($this->lexer);
        }

        return new SelectItem($path, nestedOptions: $nestedOptions);
    }
}
