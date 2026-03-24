<?php

declare(strict_types=1);

namespace NovaBytes\OData\Metadata;

/**
 * Resolves database column types to OData EDM type strings.
 */
class EdmTypeResolver
{
    /** @var array<string, string> */
    private const TYPE_MAP = [
        'string' => 'Edm.String',
        'text' => 'Edm.String',
        'char' => 'Edm.String',
        'varchar' => 'Edm.String',
        'integer' => 'Edm.Int32',
        'int' => 'Edm.Int32',
        'bigint' => 'Edm.Int64',
        'smallint' => 'Edm.Int16',
        'tinyint' => 'Edm.Int16',
        'decimal' => 'Edm.Decimal',
        'float' => 'Edm.Double',
        'double' => 'Edm.Double',
        'boolean' => 'Edm.Boolean',
        'bool' => 'Edm.Boolean',
        'datetime' => 'Edm.DateTimeOffset',
        'timestamp' => 'Edm.DateTimeOffset',
        'date' => 'Edm.Date',
        'time' => 'Edm.TimeOfDay',
        'guid' => 'Edm.Guid',
        'uuid' => 'Edm.Guid',
    ];

    /**
     * Resolve a database column type to an OData EDM type.
     *
     * @param string $columnType The database column type (e.g., "integer", "varchar", "timestamp").
     * @return string The corresponding EDM type (e.g., "Edm.Int32", "Edm.String", "Edm.DateTimeOffset").
     */
    public static function resolve(string $columnType): string
    {
        $normalized = strtolower(trim($columnType));

        return self::TYPE_MAP[$normalized] ?? 'Edm.String';
    }
}
