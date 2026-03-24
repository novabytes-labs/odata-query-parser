<?php

declare(strict_types=1);

namespace NovaBytes\OData\Metadata;

/**
 * Generates an OpenAPI 3.0 specification from entity type metadata.
 */
class OpenApiGenerator
{
    /** @var array<string, array{type: string, format?: string}> */
    private const EDM_TO_JSON_SCHEMA = [
        'Edm.String' => ['type' => 'string'],
        'Edm.Int16' => ['type' => 'integer'],
        'Edm.Int32' => ['type' => 'integer'],
        'Edm.Int64' => ['type' => 'integer', 'format' => 'int64'],
        'Edm.Decimal' => ['type' => 'number'],
        'Edm.Double' => ['type' => 'number', 'format' => 'double'],
        'Edm.Boolean' => ['type' => 'boolean'],
        'Edm.DateTimeOffset' => ['type' => 'string', 'format' => 'date-time'],
        'Edm.Date' => ['type' => 'string', 'format' => 'date'],
        'Edm.TimeOfDay' => ['type' => 'string', 'format' => 'time'],
        'Edm.Guid' => ['type' => 'string', 'format' => 'uuid'],
    ];

    /**
     * Generate an OpenAPI 3.0 specification array.
     *
     * @param list<EntityType> $entityTypes The entity types to document.
     * @param array{title?: string, version?: string, description?: string, routePrefix?: string} $options
     * @return array<string, mixed> The OpenAPI specification as a PHP array.
     */
    public static function generate(array $entityTypes, array $options = []): array
    {
        $title = $options['title'] ?? 'OData API';
        $version = $options['version'] ?? '1.0.0';
        $description = $options['description'] ?? '';
        $routePrefix = rtrim($options['routePrefix'] ?? '', '/');

        $spec = [
            'openapi' => '3.0.3',
            'info' => [
                'title' => $title,
                'version' => $version,
            ],
            'paths' => [],
            'components' => [
                'schemas' => [],
            ],
        ];

        if ($description !== '') {
            $spec['info']['description'] = $description;
        }

        foreach ($entityTypes as $entityType) {
            $path = $routePrefix !== '' ? "/{$routePrefix}/{$entityType->entitySetName}" : "/{$entityType->entitySetName}";
            $spec['paths'][$path] = self::buildPathItem($entityType);
            $spec['components']['schemas'][$entityType->name] = self::buildSchema($entityType);
        }

        return $spec;
    }

    /**
     * Build the OpenAPI path item for an entity set.
     *
     * @return array<string, mixed>
     */
    private static function buildPathItem(EntityType $entityType): array
    {
        $parameters = self::buildQueryParameters($entityType);

        return [
            'get' => [
                'summary' => "Get {$entityType->entitySetName}",
                'operationId' => "get{$entityType->entitySetName}",
                'parameters' => $parameters,
                'responses' => [
                    '200' => [
                        'description' => "List of {$entityType->name} entities.",
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'value' => [
                                            'type' => 'array',
                                            'items' => [
                                                '$ref' => "#/components/schemas/{$entityType->name}",
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Build OData query parameter definitions for an entity type.
     *
     * @return list<array<string, mixed>>
     */
    private static function buildQueryParameters(EntityType $entityType): array
    {
        $filterableProperties = self::getPropertyNamesByCapability($entityType, 'filterable');
        $selectableProperties = self::getPropertyNamesByCapability($entityType, 'selectable');
        $sortableProperties = self::getPropertyNamesByCapability($entityType, 'sortable');
        $expandableNavProperties = array_map(
            fn(NavigationPropertyMetadata $nav): string => $nav->name,
            $entityType->navigationProperties,
        );

        $parameters = [];

        if ($filterableProperties !== []) {
            $parameters[] = [
                'name' => '$filter',
                'in' => 'query',
                'required' => false,
                'schema' => ['type' => 'string'],
                'description' => 'Filter by: ' . implode(', ', $filterableProperties),
            ];
        }

        if ($selectableProperties !== []) {
            $parameters[] = [
                'name' => '$select',
                'in' => 'query',
                'required' => false,
                'schema' => ['type' => 'string'],
                'description' => 'Select properties: ' . implode(', ', $selectableProperties),
            ];
        }

        if ($expandableNavProperties !== []) {
            $parameters[] = [
                'name' => '$expand',
                'in' => 'query',
                'required' => false,
                'schema' => ['type' => 'string'],
                'description' => 'Expand relationships: ' . implode(', ', $expandableNavProperties),
            ];
        }

        if ($sortableProperties !== []) {
            $parameters[] = [
                'name' => '$orderby',
                'in' => 'query',
                'required' => false,
                'schema' => ['type' => 'string'],
                'description' => 'Sort by: ' . implode(', ', $sortableProperties),
            ];
        }

        $parameters[] = [
            'name' => '$top',
            'in' => 'query',
            'required' => false,
            'schema' => ['type' => 'integer', 'minimum' => 0],
            'description' => 'Maximum number of items to return.',
        ];

        $parameters[] = [
            'name' => '$skip',
            'in' => 'query',
            'required' => false,
            'schema' => ['type' => 'integer', 'minimum' => 0],
            'description' => 'Number of items to skip.',
        ];

        $parameters[] = [
            'name' => '$count',
            'in' => 'query',
            'required' => false,
            'schema' => ['type' => 'boolean'],
            'description' => 'Include total count of matching items.',
        ];

        return $parameters;
    }

    /**
     * Build a JSON Schema for an entity type.
     *
     * @return array<string, mixed>
     */
    private static function buildSchema(EntityType $entityType): array
    {
        $properties = [];
        $required = [];

        foreach ($entityType->properties as $property) {
            $properties[$property->name] = self::edmTypeToJsonSchema($property->edmType);

            if (!$property->nullable) {
                $required[] = $property->name;
            }
        }

        $schema = [
            'type' => 'object',
            'properties' => $properties,
        ];

        if ($required !== []) {
            $schema['required'] = $required;
        }

        return $schema;
    }

    /**
     * Convert an EDM type to a JSON Schema type definition.
     *
     * @return array{type: string, format?: string}
     */
    private static function edmTypeToJsonSchema(string $edmType): array
    {
        return self::EDM_TO_JSON_SCHEMA[$edmType] ?? ['type' => 'string'];
    }

    /**
     * Get PascalCase property names that have a specific capability enabled.
     *
     * @return list<string>
     */
    private static function getPropertyNamesByCapability(EntityType $entityType, string $capability): array
    {
        $names = [];

        foreach ($entityType->properties as $property) {
            if ($property->{$capability}) {
                $names[] = $property->name;
            }
        }

        return $names;
    }
}
