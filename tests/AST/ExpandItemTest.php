<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\AST;

use NovaBytes\OData\AST\Expand\ExpandItem;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExpandItemTest extends TestCase
{
    #[Test]
    public function it_converts_wildcard_to_string(): void
    {
        $item = new ExpandItem([], isWildcard: true);
        $this->assertSame('*', (string) $item);
    }

    #[Test]
    public function it_converts_path_to_string(): void
    {
        $item = new ExpandItem(['Products', 'Supplier']);
        $this->assertSame('Products/Supplier', (string) $item);
    }
}
