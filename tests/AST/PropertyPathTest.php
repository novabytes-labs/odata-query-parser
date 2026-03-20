<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\AST;

use NovaBytes\OData\AST\Filter\PropertyPath;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PropertyPathTest extends TestCase
{
    #[Test]
    public function it_converts_segments_to_slash_separated_string(): void
    {
        $path = new PropertyPath(['Address', 'City']);
        $this->assertSame('Address/City', (string) $path);
    }

    #[Test]
    public function it_converts_single_segment_to_string(): void
    {
        $path = new PropertyPath(['Name']);
        $this->assertSame('Name', (string) $path);
    }
}
