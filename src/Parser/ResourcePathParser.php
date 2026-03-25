<?php

declare(strict_types=1);

namespace NovaBytes\OData\Parser;

use NovaBytes\OData\AST\Path\EntityKey;
use NovaBytes\OData\AST\Path\NavigationSegment;
use NovaBytes\OData\AST\Path\ResourcePath;
use NovaBytes\OData\Exception\ParseException;

/**
 * Parses OData resource paths into ResourcePath AST nodes.
 *
 * Handles paths like:
 * - /Products
 * - /Products(1)
 * - /Products('abc')
 * - /Products(Key1=1,Key2='abc')
 * - /Products(1)/Category
 * - /Products(1)/Reviews(5)
 */
class ResourcePathParser
{
    /**
     * Parse an OData resource path string.
     *
     * @param string $path The resource path (e.g., "/Products(1)/Category" or "Products(1)").
     */
    public static function parse(string $path): ResourcePath
    {
        $path = trim($path, '/');

        if ($path === '') {
            throw new ParseException('Resource path cannot be empty');
        }

        $segments = self::splitPathSegments($path);

        if ($segments === []) {
            throw new ParseException('Resource path cannot be empty');
        }

        $firstSegment = array_shift($segments);
        [$entitySet, $key] = self::parseSegment($firstSegment);

        $navigationSegments = [];
        foreach ($segments as $segment) {
            [$property, $navKey] = self::parseSegment($segment);
            $navigationSegments[] = new NavigationSegment($property, $navKey);
        }

        return new ResourcePath($entitySet, $key, $navigationSegments);
    }

    /**
     * Split a resource path into segments on '/' while respecting parentheses.
     *
     * @return list<string>
     */
    private static function splitPathSegments(string $path): array
    {
        $segments = [];
        $current = '';
        $depth = 0;

        for ($i = 0, $len = strlen($path); $i < $len; $i++) {
            $char = $path[$i];

            if ($char === '(') {
                $depth++;
                $current .= $char;
            } elseif ($char === ')') {
                $depth--;
                $current .= $char;
            } elseif ($char === '/' && $depth === 0) {
                if ($current !== '') {
                    $segments[] = $current;
                }
                $current = '';
            } else {
                $current .= $char;
            }
        }

        if ($current !== '') {
            $segments[] = $current;
        }

        return $segments;
    }

    /**
     * Parse a single path segment into a name and optional key.
     *
     * @return array{string, EntityKey|null}
     */
    private static function parseSegment(string $segment): array
    {
        $parenPos = strpos($segment, '(');

        if ($parenPos === false) {
            self::validateIdentifier($segment);

            return [$segment, null];
        }

        $name = substr($segment, 0, $parenPos);
        self::validateIdentifier($name);

        $keyExpression = substr($segment, $parenPos + 1);

        // Strip trailing ')'
        if (!str_ends_with($keyExpression, ')')) {
            throw new ParseException("Unterminated key expression in segment '{$segment}'");
        }

        $keyExpression = substr($keyExpression, 0, -1);

        return [$name, self::parseKeyExpression($keyExpression)];
    }

    /**
     * Parse a key expression string into an EntityKey.
     *
     * Supports:
     * - Single value: "1", "'abc'", "01234567-89ab-cdef-0123-456789abcdef"
     * - Composite: "Key1=1,Key2='abc'"
     */
    private static function parseKeyExpression(string $expression): EntityKey
    {
        $expression = trim($expression);

        if ($expression === '') {
            throw new ParseException('Key expression cannot be empty');
        }

        // Check if this is a composite key (contains '=' outside of quotes)
        if (self::isCompositeKey($expression)) {
            return self::parseCompositeKey($expression);
        }

        return new EntityKey(['' => self::parseLiteralValue($expression)]);
    }

    /**
     * Determine whether a key expression is composite (has named key-value pairs).
     */
    private static function isCompositeKey(string $expression): bool
    {
        $inQuote = false;

        for ($i = 0, $len = strlen($expression); $i < $len; $i++) {
            $char = $expression[$i];

            if ($char === '\'') {
                if ($inQuote && $i + 1 < $len && $expression[$i + 1] === '\'') {
                    $i++; // Skip escaped quote

                    continue;
                }
                $inQuote = !$inQuote;
            } elseif ($char === '=' && !$inQuote) {
                return true;
            }
        }

        return false;
    }

    /**
     * Parse a composite key expression like "Key1=1,Key2='abc'".
     */
    private static function parseCompositeKey(string $expression): EntityKey
    {
        $pairs = self::splitOnComma($expression);
        $values = [];

        foreach ($pairs as $pair) {
            $eqPos = strpos($pair, '=');

            if ($eqPos === false) {
                throw new ParseException("Invalid composite key pair: '{$pair}'; expected 'Name=Value' format");
            }

            $name = trim(substr($pair, 0, $eqPos));
            $rawValue = trim(substr($pair, $eqPos + 1));

            self::validateIdentifier($name);
            $values[$name] = self::parseLiteralValue($rawValue);
        }

        if ($values === []) {
            throw new ParseException('Composite key must have at least one key-value pair');
        }

        return new EntityKey($values);
    }

    /**
     * Split a string on commas while respecting single-quoted strings.
     *
     * @return list<string>
     */
    private static function splitOnComma(string $input): array
    {
        $parts = [];
        $current = '';
        $inQuote = false;

        for ($i = 0, $len = strlen($input); $i < $len; $i++) {
            $char = $input[$i];

            if ($char === '\'') {
                if ($inQuote && $i + 1 < $len && $input[$i + 1] === '\'') {
                    $current .= "''";
                    $i++;

                    continue;
                }
                $inQuote = !$inQuote;
                $current .= $char;
            } elseif ($char === ',' && !$inQuote) {
                $parts[] = trim($current);
                $current = '';
            } else {
                $current .= $char;
            }
        }

        if ($current !== '' || $parts !== []) {
            $parts[] = trim($current);
        }

        return $parts;
    }

    /**
     * Parse a literal value from a key expression.
     *
     * Supports: integers, decimals, strings ('...'), GUIDs, true, false, null.
     */
    private static function parseLiteralValue(string $raw): int|float|string|bool|null
    {
        if ($raw === 'null') {
            return null;
        }

        if ($raw === 'true') {
            return true;
        }

        if ($raw === 'false') {
            return false;
        }

        // String literal: 'value'
        if (str_starts_with($raw, "'") && str_ends_with($raw, "'") && strlen($raw) >= 2) {
            $inner = substr($raw, 1, -1);

            // Unescape doubled quotes
            return str_replace("''", "'", $inner);
        }

        // Integer
        if (preg_match('/^-?\d+$/', $raw)) {
            return (int) $raw;
        }

        // Decimal
        if (preg_match('/^-?\d+\.\d+$/', $raw)) {
            return (float) $raw;
        }

        // GUID
        if (preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $raw)) {
            return $raw;
        }

        throw new ParseException("Invalid key literal value: '{$raw}'");
    }

    /**
     * Validate that a string is a valid OData identifier.
     */
    private static function validateIdentifier(string $name): void
    {
        if ($name === '') {
            throw new ParseException('Identifier cannot be empty');
        }

        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name)) {
            throw new ParseException("Invalid identifier: '{$name}'");
        }
    }
}
