<?php

declare(strict_types=1);

namespace NovaBytes\OData\Parser;

use NovaBytes\OData\AST\OrderBy\OrderByItem;
use NovaBytes\OData\AST\OrderBy\SortDirection;
use NovaBytes\OData\Lexer\Lexer;
use NovaBytes\OData\Lexer\TokenType;

class OrderByParser
{
    /**
     * @return list<OrderByItem>
     */
    public static function parse(string $input): array
    {
        $lexer = new Lexer($input);
        $parser = new self($lexer);
        return $parser->parseOrderByItems();
    }

    public function __construct(private Lexer $lexer) {}

    /**
     * @return list<OrderByItem>
     */
    public function parseOrderByItems(): array
    {
        $items = [$this->parseOrderByItem()];

        while ($this->lexer->is(TokenType::Comma)) {
            $this->lexer->advance();
            $items[] = $this->parseOrderByItem();
        }

        return $items;
    }

    private function parseOrderByItem(): OrderByItem
    {
        $filterParser = new FilterParser($this->lexer);
        $expression = $filterParser->parseExpression();

        $direction = SortDirection::Asc;

        if ($this->lexer->is(TokenType::Asc)) {
            $this->lexer->advance();
        } elseif ($this->lexer->is(TokenType::Desc)) {
            $this->lexer->advance();
            $direction = SortDirection::Desc;
        }

        return new OrderByItem($expression, $direction);
    }
}
