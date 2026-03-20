<?php

declare(strict_types=1);

namespace NovaBytes\OData\Parser;

use NovaBytes\OData\AST\Expand\ExpandItem;
use NovaBytes\OData\AST\OrderBy\OrderByItem;
use NovaBytes\OData\AST\QueryOptions;
use NovaBytes\OData\AST\Select\SelectItem;
use NovaBytes\OData\Exception\ParseException;

class QueryOptionParser
{
    /**
     * Parse a full OData query string (the part after '?').
     *
     * Example: '$filter=Price gt 100&$select=Name,Price&$top=10'
     */
    public static function parse(string $queryString): QueryOptions
    {
        // Percent-decode the query string first (except for %27 which is handled in the lexer)
        $queryString = self::percentDecode($queryString);

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

        $pairs = self::splitQueryString($queryString);

        foreach ($pairs as [$name, $value]) {
            // Strip '$' prefix if present (OData supports both $filter and filter)
            $normalizedName = ltrim($name, '$');

            switch ($normalizedName) {
                case 'filter':
                    $filter = FilterParser::parse($value);
                    break;

                case 'select':
                    $select = SelectParser::parse($value);
                    break;

                case 'expand':
                    $expand = ExpandParser::parse($value);
                    break;

                case 'orderby':
                    $orderby = OrderByParser::parse($value);
                    break;

                case 'top':
                    if (!ctype_digit($value)) {
                        throw new ParseException("Invalid \$top value: '{$value}'; expected a non-negative integer");
                    }
                    $top = (int) $value;
                    break;

                case 'skip':
                    if (!ctype_digit($value)) {
                        throw new ParseException("Invalid \$skip value: '{$value}'; expected a non-negative integer");
                    }
                    $skip = (int) $value;
                    break;

                case 'count':
                    if ($value !== 'true' && $value !== 'false') {
                        throw new ParseException("Invalid \$count value: '{$value}'; expected 'true' or 'false'");
                    }
                    $count = $value === 'true';
                    break;

                    // Silently ignore unknown query options (custom query options are allowed by the spec)
            }
        }

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
     * Split a query string into name=value pairs, respecting parentheses
     * (needed because $expand values can contain nested '&' and '=' inside parentheses).
     *
     * @return list<array{string, string}>
     */
    private static function splitQueryString(string $queryString): array
    {
        $pairs = [];
        $depth = 0;
        $current = '';

        for ($i = 0; $i < strlen($queryString); $i++) {
            $char = $queryString[$i];

            if ($char === '(') {
                $depth++;
                $current .= $char;
            } elseif ($char === ')') {
                $depth--;
                $current .= $char;
            } elseif ($char === '&' && $depth === 0) {
                $pairs[] = self::splitPair($current);
                $current = '';
            } else {
                $current .= $char;
            }
        }

        if ($current !== '') {
            $pairs[] = self::splitPair($current);
        }

        return $pairs;
    }

    /**
     * Split a single "name=value" pair, splitting only on the first '='.
     *
     * @return array{string, string}
     */
    private static function splitPair(string $pair): array
    {
        $eqPos = strpos($pair, '=');
        if ($eqPos === false) {
            return [$pair, ''];
        }

        return [
            substr($pair, 0, $eqPos),
            substr($pair, $eqPos + 1),
        ];
    }

    /**
     * Percent-decode a query string, but leave single quotes (%27) encoded
     * since the lexer handles them.
     */
    private static function percentDecode(string $input): string
    {
        // We selectively decode common percent-encoded characters used in OData
        // but preserve the overall structure
        $result = '';
        $len = strlen($input);

        for ($i = 0; $i < $len; $i++) {
            if ($input[$i] === '%' && $i + 2 < $len) {
                $hex = substr($input, $i + 1, 2);
                $upper = strtoupper($hex);

                // Don't decode characters that would break parsing:
                // %26 (&), %3D (=), %27 (')
                if ($upper !== '26' && $upper !== '3D' && $upper !== '27') {
                    $result .= chr((int) hexdec($hex));
                    $i += 2;
                } else {
                    $result .= $input[$i];
                }
            } else {
                $result .= $input[$i];
            }
        }

        return $result;
    }
}
