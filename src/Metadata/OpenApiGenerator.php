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
            $basePath = $routePrefix !== '' ? "/{$routePrefix}/{$entityType->entitySetName}" : "/{$entityType->entitySetName}";

            $spec['paths'][$basePath] = self::buildCollectionPathItem($entityType);

            $singlePath = "{$basePath}({{$entityType->keyProperty}})";
            $singlePathItem = self::buildSingleEntityPathItem($entityType);

            if ($singlePathItem !== []) {
                $spec['paths'][$singlePath] = $singlePathItem;
            }

            $spec['components']['schemas'][$entityType->name] = self::buildSchema($entityType);

            if (self::supportsOperation($entityType, 'create')) {
                $createSchema = self::buildCreateSchema($entityType);

                if ($createSchema !== null) {
                    $spec['components']['schemas']["{$entityType->name}Create"] = $createSchema;
                }
            }

            if (self::supportsOperation($entityType, 'update')) {
                $updateSchema = self::buildUpdateSchema($entityType);

                if ($updateSchema !== null) {
                    $spec['components']['schemas']["{$entityType->name}Update"] = $updateSchema;
                }
            }
        }

        return $spec;
    }

    /**
     * Build the OpenAPI path item for an entity set collection endpoint.
     *
     * @return array<string, mixed>
     */
    private static function buildCollectionPathItem(EntityType $entityType): array
    {
        $pathItem = [];

        if (self::supportsOperation($entityType, 'read')) {
            $parameters = self::buildQueryParameters($entityType);

            $pathItem['get'] = [
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
            ];
        }

        if (self::supportsOperation($entityType, 'create')) {
            $creatableProperties = self::getPropertyNamesByCapability($entityType, 'creatable');
            $schemaRef = $creatableProperties !== []
                ? ['$ref' => "#/components/schemas/{$entityType->name}Create"]
                : ['$ref' => "#/components/schemas/{$entityType->name}"];

            $pathItem['post'] = [
                'summary' => "Create a new {$entityType->name}",
                'operationId' => "create{$entityType->name}",
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => $schemaRef,
                        ],
                    ],
                ],
                'responses' => [
                    '201' => [
                        'description' => "The created {$entityType->name} entity.",
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => "#/components/schemas/{$entityType->name}",
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        }

        return $pathItem;
    }

    /**
     * Build the OpenAPI path item for a single entity endpoint.
     *
     * @return array<string, mixed>
     */
    private static function buildSingleEntityPathItem(EntityType $entityType): array
    {
        $pathItem = [];
        $keyParameter = self::buildKeyParameter($entityType);

        if (self::supportsOperation($entityType, 'read')) {
            $pathItem['get'] = [
                'summary' => "Get a {$entityType->name} by key",
                'operationId' => "get{$entityType->name}ByKey",
                'parameters' => [$keyParameter],
                'responses' => [
                    '200' => [
                        'description' => "The {$entityType->name} entity.",
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => "#/components/schemas/{$entityType->name}",
                                ],
                            ],
                        ],
                    ],
                    '404' => [
                        'description' => "{$entityType->name} not found.",
                    ],
                ],
            ];
        }

        if (self::supportsOperation($entityType, 'update')) {
            $updatableProperties = self::getPropertyNamesByCapability($entityType, 'updatable');
            $schemaRef = $updatableProperties !== []
                ? ['$ref' => "#/components/schemas/{$entityType->name}Update"]
                : ['$ref' => "#/components/schemas/{$entityType->name}"];

            $pathItem['put'] = [
                'summary' => "Replace a {$entityType->name}",
                'operationId' => "update{$entityType->name}",
                'parameters' => [$keyParameter],
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => $schemaRef,
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => "The updated {$entityType->name} entity.",
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => "#/components/schemas/{$entityType->name}",
                                ],
                            ],
                        ],
                    ],
                    '404' => [
                        'description' => "{$entityType->name} not found.",
                    ],
                ],
            ];

            $pathItem['patch'] = [
                'summary' => "Update a {$entityType->name}",
                'operationId' => "patch{$entityType->name}",
                'parameters' => [$keyParameter],
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => $schemaRef,
                        ],
                    ],
                ],
                'responses' => [
                    '200' => [
                        'description' => "The updated {$entityType->name} entity.",
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => "#/components/schemas/{$entityType->name}",
                                ],
                            ],
                        ],
                    ],
                    '404' => [
                        'description' => "{$entityType->name} not found.",
                    ],
                ],
            ];
        }

        if (self::supportsOperation($entityType, 'delete')) {
            $pathItem['delete'] = [
                'summary' => "Delete a {$entityType->name}",
                'operationId' => "delete{$entityType->name}",
                'parameters' => [$keyParameter],
                'responses' => [
                    '204' => [
                        'description' => "{$entityType->name} deleted successfully.",
                    ],
                    '404' => [
                        'description' => "{$entityType->name} not found.",
                    ],
                ],
            ];
        }

        return $pathItem;
    }

    /**
     * Build the key parameter definition for single entity paths.
     *
     * @return array<string, mixed>
     */
    private static function buildKeyParameter(EntityType $entityType): array
    {
        $keyProperty = null;

        foreach ($entityType->properties as $property) {
            if ($property->name === $entityType->keyProperty) {
                $keyProperty = $property;

                break;
            }
        }

        $schema = $keyProperty !== null
            ? self::edmTypeToJsonSchema($keyProperty->edmType)
            : ['type' => 'string'];

        return [
            'name' => $entityType->keyProperty,
            'in' => 'path',
            'required' => true,
            'schema' => $schema,
            'description' => "The {$entityType->name} key.",
        ];
    }

    /**
     * Build a JSON Schema for creatable properties of an entity type.
     *
     * @return array<string, mixed>|null
     */
    private static function buildCreateSchema(EntityType $entityType): ?array
    {
        $creatableProperties = self::getPropertyNamesByCapability($entityType, 'creatable');

        if ($creatableProperties === []) {
            return null;
        }

        $properties = [];
        $required = [];

        foreach ($entityType->properties as $property) {
            if (!$property->creatable) {
                continue;
            }

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
     * Build a JSON Schema for updatable properties of an entity type.
     *
     * All properties are optional in update schemas (for PATCH support).
     *
     * @return array<string, mixed>|null
     */
    private static function buildUpdateSchema(EntityType $entityType): ?array
    {
        $updatableProperties = self::getPropertyNamesByCapability($entityType, 'updatable');

        if ($updatableProperties === []) {
            return null;
        }

        $properties = [];

        foreach ($entityType->properties as $property) {
            if (!$property->updatable) {
                continue;
            }

            $properties[$property->name] = self::edmTypeToJsonSchema($property->edmType);
        }

        return [
            'type' => 'object',
            'properties' => $properties,
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
     * Check whether an entity type supports a given operation.
     */
    private static function supportsOperation(EntityType $entityType, string $operation): bool
    {
        return in_array($operation, $entityType->operations, true);
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
