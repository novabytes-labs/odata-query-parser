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
    public function visitBinaryExpression(BinaryExpression $expr): mixed;

    public function visitUnaryExpression(UnaryExpression $expr): mixed;

    public function visitPropertyPath(PropertyPath $expr): mixed;

    public function visitLiteral(Literal $expr): mixed;

    public function visitFunctionCall(FunctionCall $expr): mixed;

    public function visitLambdaExpression(LambdaExpression $expr): mixed;

    public function visitListExpression(ListExpression $expr): mixed;
}
