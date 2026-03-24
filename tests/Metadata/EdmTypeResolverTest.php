<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Metadata;

use NovaBytes\OData\Metadata\EdmTypeResolver;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class EdmTypeResolverTest extends TestCase
{
    /**
     * @return array<string, array{string, string}>
     */
    public static function typeProvider(): array
    {
        return [
            'string' => ['string', 'Edm.String'],
            'text' => ['text', 'Edm.String'],
            'char' => ['char', 'Edm.String'],
            'varchar' => ['varchar', 'Edm.String'],
            'integer' => ['integer', 'Edm.Int32'],
            'int' => ['int', 'Edm.Int32'],
            'bigint' => ['bigint', 'Edm.Int64'],
            'smallint' => ['smallint', 'Edm.Int16'],
            'tinyint' => ['tinyint', 'Edm.Int16'],
            'decimal' => ['decimal', 'Edm.Decimal'],
            'float' => ['float', 'Edm.Double'],
            'double' => ['double', 'Edm.Double'],
            'boolean' => ['boolean', 'Edm.Boolean'],
            'bool' => ['bool', 'Edm.Boolean'],
            'datetime' => ['datetime', 'Edm.DateTimeOffset'],
            'timestamp' => ['timestamp', 'Edm.DateTimeOffset'],
            'date' => ['date', 'Edm.Date'],
            'time' => ['time', 'Edm.TimeOfDay'],
            'guid' => ['guid', 'Edm.Guid'],
            'uuid' => ['uuid', 'Edm.Guid'],
        ];
    }

    #[Test]
    #[DataProvider('typeProvider')]
    public function it_resolves_known_types(string $columnType, string $expectedEdmType): void
    {
        $this->assertSame($expectedEdmType, EdmTypeResolver::resolve($columnType));
    }

    #[Test]
    public function it_falls_back_to_edm_string_for_unknown_types(): void
    {
        $this->assertSame('Edm.String', EdmTypeResolver::resolve('binary'));
        $this->assertSame('Edm.String', EdmTypeResolver::resolve('blob'));
        $this->assertSame('Edm.String', EdmTypeResolver::resolve('unknown'));
    }

    #[Test]
    public function it_normalizes_case_and_whitespace(): void
    {
        $this->assertSame('Edm.Int32', EdmTypeResolver::resolve('INTEGER'));
        $this->assertSame('Edm.Boolean', EdmTypeResolver::resolve(' Boolean '));
        $this->assertSame('Edm.String', EdmTypeResolver::resolve('STRING'));
    }
}
