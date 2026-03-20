<?php

declare(strict_types=1);

namespace NovaBytes\OData\Parser;

use NovaBytes\OData\AST\Expression;
use NovaBytes\OData\AST\Expand\ExpandItem;
use NovaBytes\OData\AST\OrderBy\OrderByItem;
use NovaBytes\OData\AST\QueryOptions;
use NovaBytes\OData\AST\Select\SelectItem;
use NovaBytes\OData\Exception\ParseException;
use NovaBytes\OData\Lexer\Lexer;
use NovaBytes\OData\Lexer\TokenType;

/**
 * Parses nested query options inside parentheses, separated by semicolons.
 * Used by $expand and $select: e.g. Products($filter=Price gt 100;$top=5;$select=Name)
 */
class NestedOptionsParser
{
    public static function parse(Lexer $lexer): QueryOptions
    {
        $lexer->expect(TokenType::OpenParen);

        $filter = null;
        /** @var list<SelectItem>|null $select */
        $select = null;
        /** @var list<ExpandItem>|null $expand */
        $expand = null;
        /** @var list<OrderByItem>|null $orderby */
        $orderby = null;
        $top = null;
        $skip = null;
        $count = null;

        while (!$lexer->is(TokenType::CloseParen) && !$lexer->isEof()) {
            // Expect an identifier starting with '$' or without
            $optionName = self::readOptionName($lexer);

            $lexer->expect(TokenType::Equals);

            switch ($optionName) {
                case 'filter':
                    $filterParser = new FilterParser($lexer);
                    $filter = $filterParser->parseExpression();
                    break;

                case 'select':
                    $selectParser = new SelectParser($lexer);
                    $select = $selectParser->parseSelectItems();
                    break;

                case 'expand':
                    $expandParser = new ExpandParser($lexer);
                    $expand = $expandParser->parseExpandItems();
                    break;

                case 'orderby':
                    $orderbyParser = new OrderByParser($lexer);
                    $orderby = $orderbyParser->parseOrderByItems();
                    break;

                case 'top':
                    $top = (int) $lexer->expect(TokenType::Integer)->value;
                    break;

                case 'skip':
                    $skip = (int) $lexer->expect(TokenType::Integer)->value;
                    break;

                case 'count':
                    $token = $lexer->current();
                    if ($token->type === TokenType::True) {
                        $count = true;
                    } elseif ($token->type === TokenType::False) {
                        $count = false;
                    } else {
                        throw ParseException::unexpectedToken('true or false', $token->value, $token->position);
                    }
                    $lexer->advance();
                    break;

                default:
                    throw new ParseException(
                        "Unknown nested query option '\${$optionName}' at position {$lexer->current()->position}",
                        $lexer->current()->position,
                    );
            }

            // Options separated by ';'
            if ($lexer->is(TokenType::Semicolon)) {
                $lexer->advance();
            }
        }

        $lexer->expect(TokenType::CloseParen);

        return new QueryOptions(
            filter: $filter,
            select: $select,
            expand: $expand,
            orderby: $orderby,
            top: $top,
            skip: $skip,
            count: $count,
        );
    }

    /**
     * Reads a query option name, stripping the optional '$' prefix.
     * The lexer tokenizes '$filter' as '$' is not a recognized token,
     * so we handle the raw name from the identifier, which may include
     * the '$' prefix from the original input. Since we're inside nested
     * options, the option name comes as an identifier.
     */
    private static function readOptionName(Lexer $lexer): string
    {
        $token = $lexer->current();

        // The option name is tokenized as an identifier (e.g., "$filter" → identifier "$filter")
        // but actually the '$' is dropped during lexing. The nested context provides
        // option names without '$'. The raw text we get is just the name.
        if ($token->type !== TokenType::Identifier) {
            throw ParseException::unexpectedToken(
                'query option name',
                $token->value ?: 'end of input',
                $token->position,
            );
        }

        $name = $lexer->advance()->value;

        // Strip leading '$' if present
        if (str_starts_with($name, '$')) {
            $name = substr($name, 1);
        }

        return $name;
    }
}
