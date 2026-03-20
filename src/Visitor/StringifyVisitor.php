<?php

declare(strict_types=1);

namespace NovaBytes\OData\Visitor;

use NovaBytes\OData\AST\Expression;
use NovaBytes\OData\AST\Filter\BinaryExpression;
use NovaBytes\OData\AST\Filter\FunctionCall;
use NovaBytes\OData\AST\Filter\LambdaExpression;
use NovaBytes\OData\AST\Filter\ListExpression;
use NovaBytes\OData\AST\Filter\Literal;
use NovaBytes\OData\AST\Filter\LiteralType;
use NovaBytes\OData\AST\Filter\PropertyPath;
use NovaBytes\OData\AST\Filter\UnaryExpression;
use NovaBytes\OData\AST\Filter\UnaryOperator;

class StringifyVisitor implements ExpressionVisitor
{
    /**
     * Convert an expression AST back to its OData string representation.
     */
    public function stringify(Expression $expr): string
    {
        return (string) $this->visit($expr);
    }

    /**
     * Dispatch to the appropriate visit method based on the expression type.
     */
    public function visit(Expression $expr): string
    {
        return match (true) {
            $expr instanceof BinaryExpression => $this->visitBinaryExpression($expr),
            $expr instanceof UnaryExpression => $this->visitUnaryExpression($expr),
            $expr instanceof PropertyPath => $this->visitPropertyPath($expr),
            $expr instanceof Literal => $this->visitLiteral($expr),
            $expr instanceof FunctionCall => $this->visitFunctionCall($expr),
            $expr instanceof LambdaExpression => $this->visitLambdaExpression($expr),
            $expr instanceof ListExpression => $this->visitListExpression($expr),
            default => throw new \InvalidArgumentException('Unknown expression type: ' . get_class($expr)),
        };
    }

    /**
     * Stringify a binary expression (e.g. "Price gt 100").
     */
    public function visitBinaryExpression(BinaryExpression $expr): string
    {
        $left = $this->visit($expr->left);
        $right = $this->visit($expr->right);
        return "{$left} {$expr->operator->value} {$right}";
    }

    /**
     * Stringify a unary expression (not or negation).
     */
    public function visitUnaryExpression(UnaryExpression $expr): string
    {
        $operand = $this->visit($expr->operand);

        if ($expr->operator === UnaryOperator::Negate) {
            return "-{$operand}";
        }

        return "not {$operand}";
    }

    /**
     * Stringify a property path (e.g. "Address/City").
     */
    public function visitPropertyPath(PropertyPath $expr): string
    {
        return (string) $expr;
    }

    /**
     * Stringify a literal value according to its OData type.
     */
    public function visitLiteral(Literal $expr): string
    {
        return match ($expr->type) {
            LiteralType::Null => 'null',
            LiteralType::Boolean => $expr->value ? 'true' : 'false',
            LiteralType::Integer => (string) $expr->value,
            LiteralType::Decimal => $this->formatDecimal($expr->value),
            LiteralType::String => "'" . str_replace("'", "''", (string) $expr->value) . "'",
            LiteralType::Guid,
            LiteralType::Date,
            LiteralType::DateTimeOffset,
            LiteralType::TimeOfDay => (string) $expr->value,
            LiteralType::Duration => "duration'{$expr->value}'",
        };
    }

    /**
     * Stringify a function call (e.g. "contains(Name,'value')").
     */
    public function visitFunctionCall(FunctionCall $expr): string
    {
        $args = array_map(fn(Expression $arg) => $this->visit($arg), $expr->arguments);
        return $expr->name . '(' . implode(',', $args) . ')';
    }

    /**
     * Stringify a lambda expression (e.g. "Items/any(i:i/Price gt 100)").
     */
    public function visitLambdaExpression(LambdaExpression $expr): string
    {
        $collection = $this->visit($expr->collection);

        if ($expr->variable === null) {
            return "{$collection}/{$expr->operator->value}()";
        }

        $predicate = $this->visit($expr->predicate);
        return "{$collection}/{$expr->operator->value}({$expr->variable}:{$predicate})";
    }

    /**
     * Stringify a list expression (e.g. "(1,2,3)").
     */
    public function visitListExpression(ListExpression $expr): string
    {
        $items = array_map(fn(Expression $item) => $this->visit($item), $expr->items);
        return '(' . implode(',', $items) . ')';
    }

    /**
     * Format a decimal value, handling NaN, INF, and -INF special cases.
     */
    private function formatDecimal(mixed $value): string
    {
        if (is_float($value)) {
            if (is_nan($value)) {
                return 'NaN';
            }
            if (is_infinite($value)) {
                return $value > 0 ? 'INF' : '-INF';
            }
        }

        return (string) $value;
    }
}
