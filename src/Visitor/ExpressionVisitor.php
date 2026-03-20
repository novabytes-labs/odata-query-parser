<?php

declare(strict_types=1);

namespace NovaBytes\OData\Visitor;

use NovaBytes\OData\AST\Filter\BinaryExpression;
use NovaBytes\OData\AST\Filter\FunctionCall;
use NovaBytes\OData\AST\Filter\LambdaExpression;
use NovaBytes\OData\AST\Filter\ListExpression;
use NovaBytes\OData\AST\Filter\Literal;
use NovaBytes\OData\AST\Filter\PropertyPath;
use NovaBytes\OData\AST\Filter\UnaryExpression;

interface ExpressionVisitor
{
    /**
     * Visit a binary expression node (e.g. left op right).
     */
    public function visitBinaryExpression(BinaryExpression $expr): mixed;

    /**
     * Visit a unary expression node (not or negation).
     */
    public function visitUnaryExpression(UnaryExpression $expr): mixed;

    /**
     * Visit a property path node (e.g. Address/City).
     */
    public function visitPropertyPath(PropertyPath $expr): mixed;

    /**
     * Visit a literal value node.
     */
    public function visitLiteral(Literal $expr): mixed;

    /**
     * Visit a function call node.
     */
    public function visitFunctionCall(FunctionCall $expr): mixed;

    /**
     * Visit a lambda expression node (any/all).
     */
    public function visitLambdaExpression(LambdaExpression $expr): mixed;

    /**
     * Visit a list expression node (comma-separated values in parentheses).
     */
    public function visitListExpression(ListExpression $expr): mixed;
}
