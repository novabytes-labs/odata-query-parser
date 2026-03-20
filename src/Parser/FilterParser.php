<?php

declare(strict_types=1);

namespace NovaBytes\OData\Parser;

use NovaBytes\OData\AST\Expression;
use NovaBytes\OData\AST\Filter\BinaryExpression;
use NovaBytes\OData\AST\Filter\BinaryOperator;
use NovaBytes\OData\AST\Filter\FunctionCall;
use NovaBytes\OData\AST\Filter\LambdaExpression;
use NovaBytes\OData\AST\Filter\LambdaOperator;
use NovaBytes\OData\AST\Filter\ListExpression;
use NovaBytes\OData\AST\Filter\Literal;
use NovaBytes\OData\AST\Filter\LiteralType;
use NovaBytes\OData\AST\Filter\PropertyPath;
use NovaBytes\OData\AST\Filter\UnaryExpression;
use NovaBytes\OData\AST\Filter\UnaryOperator;
use NovaBytes\OData\Exception\ParseException;
use NovaBytes\OData\Lexer\Lexer;
use NovaBytes\OData\Lexer\Token;
use NovaBytes\OData\Lexer\TokenType;

class FilterParser
{
    /**
     * Built-in method call functions and their argument counts.
     * null means variable args (like substring which takes 2 or 3).
     *
     * @var array<string, int|null>
     */
    private const BUILTIN_FUNCTIONS = [
        // String functions
        'contains' => 2,
        'endswith' => 2,
        'startswith' => 2,
        'length' => 1,
        'indexof' => 2,
        'substring' => null,
        'tolower' => 1,
        'toupper' => 1,
        'trim' => 1,
        'concat' => 2,
        'matchesPattern' => 2,
        // Date functions
        'year' => 1,
        'month' => 1,
        'day' => 1,
        'hour' => 1,
        'minute' => 1,
        'second' => 1,
        'fractionalseconds' => 1,
        'totalseconds' => 1,
        'date' => 1,
        'time' => 1,
        'totaloffsetminutes' => 1,
        'mindatetime' => 0,
        'maxdatetime' => 0,
        'now' => 0,
        // Math functions
        'round' => 1,
        'floor' => 1,
        'ceiling' => 1,
        // Geo functions
        'geo.distance' => 2,
        'geo.length' => 1,
        'geo.intersects' => 2,
        // Collection functions
        'hassubset' => 2,
        'hassubsequence' => 2,
        // Type functions
        'cast' => null,
        'isof' => null,
    ];

    /**
     * Operator binding powers (precedence) for the Pratt parser.
     * Higher = tighter binding.
     */
    private const BINDING_POWER = [
        'or' => 10,
        'and' => 20,
        'eq' => 30,
        'ne' => 30,
        'gt' => 40,
        'ge' => 40,
        'lt' => 40,
        'le' => 40,
        'in' => 40,
        'has' => 40,
        'add' => 50,
        'sub' => 50,
        'mul' => 60,
        'div' => 60,
        'divby' => 60,
        'mod' => 60,
    ];

    private Lexer $lexer;

    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;
    }

    public static function parse(string $input): Expression
    {
        $lexer = new Lexer($input);
        $parser = new self($lexer);
        $expr = $parser->parseExpression();

        if (!$parser->lexer->isEof()) {
            $token = $parser->lexer->current();
            throw ParseException::unexpectedToken(
                'end of input',
                $token->value,
                $token->position,
            );
        }

        return $expr;
    }

    public function parseExpression(int $minBp = 0): Expression
    {
        $left = $this->parsePrefix();

        while (true) {
            $token = $this->lexer->current();
            $bp = $this->getBindingPower($token);

            if ($bp === null || $bp <= $minBp) {
                break;
            }

            $operator = $this->tokenToBinaryOperator($token);
            $this->lexer->advance();
            $right = $this->parseExpression($bp);

            $left = new BinaryExpression($left, $operator, $right);
        }

        return $left;
    }

    private function parsePrefix(): Expression
    {
        $token = $this->lexer->current();

        return match ($token->type) {
            // Literals
            TokenType::Null => $this->parseLiteral($token, null, LiteralType::Null),
            TokenType::True => $this->parseLiteral($token, true, LiteralType::Boolean),
            TokenType::False => $this->parseLiteral($token, false, LiteralType::Boolean),
            TokenType::Integer => $this->parseLiteral($token, (int) $token->value, LiteralType::Integer),
            TokenType::Decimal => $this->parseDecimalLiteral($token),
            TokenType::String => $this->parseLiteral($token, $token->value, LiteralType::String),
            TokenType::Guid => $this->parseLiteral($token, $token->value, LiteralType::Guid),
            TokenType::Date => $this->parseLiteral($token, $token->value, LiteralType::Date),
            TokenType::DateTimeOffset => $this->parseLiteral($token, $token->value, LiteralType::DateTimeOffset),
            TokenType::TimeOfDay => $this->parseLiteral($token, $token->value, LiteralType::TimeOfDay),
            TokenType::Duration => $this->parseLiteral($token, $token->value, LiteralType::Duration),

            // Unary operators
            TokenType::Not => $this->parseNot(),
            TokenType::Minus => $this->parseNegate(),

            // Grouping / list expression
            TokenType::OpenParen => $this->parseParenOrList(),

            // Identifiers: could be property path, function call, or lambda
            TokenType::Identifier => $this->parseIdentifierExpression(),

            default => throw ParseException::unexpectedToken(
                'expression',
                $token->value ?: 'end of input',
                $token->position,
            ),
        };
    }

    private function parseLiteral(Token $token, string|int|float|bool|null $value, LiteralType $type): Literal
    {
        $this->lexer->advance();
        return new Literal($value, $type);
    }

    private function parseDecimalLiteral(Token $token): Literal
    {
        $this->lexer->advance();

        $value = match ($token->value) {
            'NaN' => NAN,
            'INF' => INF,
            '-INF' => -INF,
            default => (float) $token->value,
        };

        return new Literal($value, LiteralType::Decimal);
    }

    private function parseNot(): UnaryExpression
    {
        $this->lexer->advance(); // consume 'not'
        $operand = $this->parseExpression(85); // high binding power for unary
        return new UnaryExpression(UnaryOperator::Not, $operand);
    }

    private function parseNegate(): UnaryExpression
    {
        $this->lexer->advance(); // consume '-'
        $operand = $this->parseExpression(85);
        return new UnaryExpression(UnaryOperator::Negate, $operand);
    }

    private function parseParenOrList(): Expression
    {
        $this->lexer->advance(); // consume '('
        $first = $this->parseExpression();

        // Check for list expression: (expr, expr, ...)
        if ($this->lexer->is(TokenType::Comma)) {
            $items = [$first];
            while ($this->lexer->is(TokenType::Comma)) {
                $this->lexer->advance();
                $items[] = $this->parseExpression();
            }
            $this->lexer->expect(TokenType::CloseParen);
            return new ListExpression($items);
        }

        $this->lexer->expect(TokenType::CloseParen);
        return $first; // just grouping
    }

    private function parseIdentifierExpression(): Expression
    {
        $name = $this->lexer->current()->value;

        // Check for built-in function call: name(...)
        if ($this->isBuiltinFunction($name) && $this->lexer->peek()->type === TokenType::OpenParen) {
            return $this->parseFunctionCall();
        }

        // Otherwise it's a property path, possibly followed by /any() or /all()
        return $this->parsePropertyPathOrLambda();
    }

    private function parseFunctionCall(): FunctionCall
    {
        $name = $this->lexer->advance()->value; // consume function name

        // Handle geo.xxx functions
        if ($this->lexer->is(TokenType::Dot) && isset(self::BUILTIN_FUNCTIONS['geo.' . $name])) {
            // Actually handled below — but 'geo' won't match as a function.
            // geo.distance etc are handled by checking the full name.
        }

        $this->lexer->expect(TokenType::OpenParen);
        $args = [];

        if (!$this->lexer->is(TokenType::CloseParen)) {
            $args[] = $this->parseExpression();

            while ($this->lexer->is(TokenType::Comma)) {
                $this->lexer->advance();
                $args[] = $this->parseExpression();
            }
        }

        $this->lexer->expect(TokenType::CloseParen);

        return new FunctionCall($name, $args);
    }

    private function parsePropertyPathOrLambda(): Expression
    {
        $segments = [$this->lexer->advance()->value]; // consume first identifier

        while ($this->lexer->is(TokenType::Slash)) {
            $this->lexer->advance(); // consume '/'

            $next = $this->lexer->current();

            // Check for lambda: path/any(...) or path/all(...)
            if ($next->type === TokenType::Any || $next->type === TokenType::All) {
                $operator = $next->type === TokenType::Any ? LambdaOperator::Any : LambdaOperator::All;
                $this->lexer->advance(); // consume 'any'/'all'
                return $this->parseLambda(new PropertyPath($segments), $operator);
            }

            // Check for function call on path: path/functionName(...)
            if ($next->type === TokenType::Identifier) {
                // Check if this identifier followed by '(' is a built-in function
                if ($this->isBuiltinFunction($next->value) && $this->lexer->peek()->type === TokenType::OpenParen) {
                    $collection = new PropertyPath($segments);
                    $funcName = $this->lexer->advance()->value;
                    $this->lexer->expect(TokenType::OpenParen);
                    $args = [];
                    if (!$this->lexer->is(TokenType::CloseParen)) {
                        $args[] = $this->parseExpression();
                        while ($this->lexer->is(TokenType::Comma)) {
                            $this->lexer->advance();
                            $args[] = $this->parseExpression();
                        }
                    }
                    $this->lexer->expect(TokenType::CloseParen);
                    return new FunctionCall($funcName, [$collection, ...$args]);
                }

                $segments[] = $this->lexer->advance()->value;
            } else {
                throw ParseException::unexpectedToken(
                    'identifier',
                    $next->value ?: 'end of input',
                    $next->position,
                );
            }
        }

        return new PropertyPath($segments);
    }

    private function parseLambda(Expression $collection, LambdaOperator $operator): LambdaExpression
    {
        $this->lexer->expect(TokenType::OpenParen);

        // any() with no args is valid
        if ($this->lexer->is(TokenType::CloseParen)) {
            $this->lexer->advance();
            return new LambdaExpression($collection, $operator, null, null);
        }

        // variable : predicate
        $variable = $this->lexer->expect(TokenType::Identifier)->value;
        $this->lexer->expect(TokenType::Colon);
        $predicate = $this->parseExpression();
        $this->lexer->expect(TokenType::CloseParen);

        return new LambdaExpression($collection, $operator, $variable, $predicate);
    }

    private function isBuiltinFunction(string $name): bool
    {
        return array_key_exists($name, self::BUILTIN_FUNCTIONS);
    }

    private function getBindingPower(Token $token): ?int
    {
        return self::BINDING_POWER[$token->type->value] ?? null;
    }

    private function tokenToBinaryOperator(Token $token): BinaryOperator
    {
        return BinaryOperator::from($token->type->value);
    }
}
