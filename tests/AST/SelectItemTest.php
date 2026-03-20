<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\AST;

use NovaBytes\OData\AST\Select\SelectItem;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SelectItemTest extends TestCase
{
    #[Test]
    public function it_converts_wildcard_to_string(): void
    {
        $item = new SelectItem([], isWildcard: true);
        $this->assertSame('*', (string) $item);
    }

    #[Test]
    public function it_converts_path_to_string(): void
    {
        $item = new SelectItem(['Address', 'City']);
        $this->assertSame('Address/City', (string) $item);
    }

    #[Test]
    public function it_converts_single_segment_to_string(): void
    {
        $item = new SelectItem(['Name']);
        $this->assertSame('Name', (string) $item);
    }
}
