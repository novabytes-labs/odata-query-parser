<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Metadata;

use NovaBytes\OData\Metadata\EntityType;
use NovaBytes\OData\Metadata\NavigationPropertyMetadata;
use NovaBytes\OData\Metadata\OpenApiGenerator;
use NovaBytes\OData\Metadata\PropertyMetadata;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class OpenApiGeneratorTest extends TestCase
{
    #[Test]
    public function it_generates_valid_openapi_structure(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false, filterable: true, sortable: true, selectable: true),
                    new PropertyMetadata('Name', 'Edm.String', nullable: false, filterable: true, sortable: true, selectable: true),
                    new PropertyMetadata('Price', 'Edm.Decimal', nullable: false, filterable: true, sortable: true, selectable: true),
                ],
                navigationProperties: [
                    new NavigationPropertyMetadata('Category', 'Category', isCollection: false),
                ],
            ),
        ];

        $spec = OpenApiGenerator::generate($entityTypes);

        $this->assertSame('3.0.3', $spec['openapi']);
        $this->assertSame('OData API', $spec['info']['title']);
        $this->assertSame('1.0.0', $spec['info']['version']);
        $this->assertArrayHasKey('/Products', $spec['paths']);
        $this->assertArrayHasKey('Product', $spec['components']['schemas']);
    }

    #[Test]
    public function it_uses_custom_options(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                ],
            ),
        ];

        $spec = OpenApiGenerator::generate($entityTypes, [
            'title' => 'My API',
            'version' => '2.0.0',
            'description' => 'My OData API',
            'routePrefix' => 'api/odata',
        ]);

        $this->assertSame('My API', $spec['info']['title']);
        $this->assertSame('2.0.0', $spec['info']['version']);
        $this->assertSame('My OData API', $spec['info']['description']);
        $this->assertArrayHasKey('/api/odata/Products', $spec['paths']);
    }

    #[Test]
    public function it_generates_query_parameters_from_capabilities(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false, selectable: true),
                    new PropertyMetadata('Name', 'Edm.String', nullable: false, filterable: true, sortable: true, selectable: true),
                    new PropertyMetadata('Secret', 'Edm.String', nullable: true),
                ],
                navigationProperties: [
                    new NavigationPropertyMetadata('Reviews', 'Review', isCollection: true),
                ],
            ),
        ];

        $spec = OpenApiGenerator::generate($entityTypes);
        $parameters = $spec['paths']['/Products']['get']['parameters'];

        $paramNames = array_column($parameters, 'name');
        $this->assertContains('$filter', $paramNames);
        $this->assertContains('$select', $paramNames);
        $this->assertContains('$expand', $paramNames);
        $this->assertContains('$orderby', $paramNames);
        $this->assertContains('$top', $paramNames);
        $this->assertContains('$skip', $paramNames);
        $this->assertContains('$count', $paramNames);

        $filterParam = $this->findParameter($parameters, '$filter');
        $this->assertStringContainsString('Name', $filterParam['description']);
        $this->assertStringNotContainsString('Secret', $filterParam['description']);

        $selectParam = $this->findParameter($parameters, '$select');
        $this->assertStringContainsString('Id', $selectParam['description']);
        $this->assertStringContainsString('Name', $selectParam['description']);
        $this->assertStringNotContainsString('Secret', $selectParam['description']);
    }

    #[Test]
    public function it_generates_component_schemas_with_correct_types(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                    new PropertyMetadata('Name', 'Edm.String', nullable: false),
                    new PropertyMetadata('Price', 'Edm.Decimal', nullable: true),
                    new PropertyMetadata('IsActive', 'Edm.Boolean', nullable: false),
                    new PropertyMetadata('CreatedAt', 'Edm.DateTimeOffset', nullable: false),
                ],
            ),
        ];

        $spec = OpenApiGenerator::generate($entityTypes);
        $schema = $spec['components']['schemas']['Product'];

        $this->assertSame('object', $schema['type']);
        $this->assertSame(['type' => 'integer', 'format' => 'int64'], $schema['properties']['Id']);
        $this->assertSame(['type' => 'string'], $schema['properties']['Name']);
        $this->assertSame(['type' => 'number'], $schema['properties']['Price']);
        $this->assertSame(['type' => 'boolean'], $schema['properties']['IsActive']);
        $this->assertSame(['type' => 'string', 'format' => 'date-time'], $schema['properties']['CreatedAt']);

        $this->assertContains('Id', $schema['required']);
        $this->assertContains('Name', $schema['required']);
        $this->assertNotContains('Price', $schema['required']);
    }

    #[Test]
    public function it_generates_empty_spec_with_no_entity_types(): void
    {
        $spec = OpenApiGenerator::generate([]);

        $this->assertSame('3.0.3', $spec['openapi']);
        $this->assertSame([], $spec['paths']);
        $this->assertSame([], $spec['components']['schemas']);
    }

    #[Test]
    public function it_generates_single_entity_path_for_read(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                    new PropertyMetadata('Name', 'Edm.String', nullable: false),
                ],
                operations: ['read'],
            ),
        ];

        $spec = OpenApiGenerator::generate($entityTypes);

        $this->assertArrayHasKey('/Products({Id})', $spec['paths']);

        $singlePath = $spec['paths']['/Products({Id})'];
        $this->assertArrayHasKey('get', $singlePath);
        $this->assertSame('Get a Product by key', $singlePath['get']['summary']);
        $this->assertArrayHasKey('200', $singlePath['get']['responses']);
        $this->assertArrayHasKey('404', $singlePath['get']['responses']);
    }

    #[Test]
    public function it_generates_post_operation_for_create(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                    new PropertyMetadata('Name', 'Edm.String', nullable: false, creatable: true),
                    new PropertyMetadata('Price', 'Edm.Decimal', nullable: true, creatable: true),
                ],
                operations: ['read', 'create'],
            ),
        ];

        $spec = OpenApiGenerator::generate($entityTypes);

        $collectionPath = $spec['paths']['/Products'];
        $this->assertArrayHasKey('post', $collectionPath);
        $this->assertSame('Create a new Product', $collectionPath['post']['summary']);
        $this->assertArrayHasKey('201', $collectionPath['post']['responses']);

        // Create schema should exist
        $this->assertArrayHasKey('ProductCreate', $spec['components']['schemas']);

        $createSchema = $spec['components']['schemas']['ProductCreate'];
        $this->assertArrayHasKey('Name', $createSchema['properties']);
        $this->assertArrayHasKey('Price', $createSchema['properties']);
        $this->assertArrayNotHasKey('Id', $createSchema['properties']);
        $this->assertContains('Name', $createSchema['required']);
    }

    #[Test]
    public function it_generates_put_and_patch_operations_for_update(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                    new PropertyMetadata('Name', 'Edm.String', nullable: false, updatable: true),
                    new PropertyMetadata('Price', 'Edm.Decimal', nullable: true, updatable: true),
                ],
                operations: ['read', 'update'],
            ),
        ];

        $spec = OpenApiGenerator::generate($entityTypes);

        $singlePath = $spec['paths']['/Products({Id})'];
        $this->assertArrayHasKey('put', $singlePath);
        $this->assertArrayHasKey('patch', $singlePath);
        $this->assertSame('Replace a Product', $singlePath['put']['summary']);
        $this->assertSame('Update a Product', $singlePath['patch']['summary']);

        // Update schema should exist and have no required fields (for PATCH)
        $this->assertArrayHasKey('ProductUpdate', $spec['components']['schemas']);

        $updateSchema = $spec['components']['schemas']['ProductUpdate'];
        $this->assertArrayHasKey('Name', $updateSchema['properties']);
        $this->assertArrayHasKey('Price', $updateSchema['properties']);
        $this->assertArrayNotHasKey('required', $updateSchema);
    }

    #[Test]
    public function it_generates_delete_operation(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                ],
                operations: ['read', 'delete'],
            ),
        ];

        $spec = OpenApiGenerator::generate($entityTypes);

        $singlePath = $spec['paths']['/Products({Id})'];
        $this->assertArrayHasKey('delete', $singlePath);
        $this->assertSame('Delete a Product', $singlePath['delete']['summary']);
        $this->assertArrayHasKey('204', $singlePath['delete']['responses']);
        $this->assertArrayHasKey('404', $singlePath['delete']['responses']);
    }

    #[Test]
    public function it_generates_full_crud_operations(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                    new PropertyMetadata('Name', 'Edm.String', nullable: false, filterable: true, creatable: true, updatable: true),
                ],
                operations: ['read', 'create', 'update', 'delete'],
            ),
        ];

        $spec = OpenApiGenerator::generate($entityTypes);

        // Collection path has GET and POST
        $this->assertArrayHasKey('get', $spec['paths']['/Products']);
        $this->assertArrayHasKey('post', $spec['paths']['/Products']);

        // Single entity path has GET, PUT, PATCH, DELETE
        $this->assertArrayHasKey('get', $spec['paths']['/Products({Id})']);
        $this->assertArrayHasKey('put', $spec['paths']['/Products({Id})']);
        $this->assertArrayHasKey('patch', $spec['paths']['/Products({Id})']);
        $this->assertArrayHasKey('delete', $spec['paths']['/Products({Id})']);
    }

    #[Test]
    public function it_omits_operations_not_in_operations_list(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                ],
                operations: ['create'],
            ),
        ];

        $spec = OpenApiGenerator::generate($entityTypes);

        // Collection path has POST but not GET
        $this->assertArrayNotHasKey('get', $spec['paths']['/Products']);
        $this->assertArrayHasKey('post', $spec['paths']['/Products']);

        // No single entity path since read/update/delete are not allowed
        $this->assertArrayNotHasKey('/Products({Id})', $spec['paths']);
    }

    #[Test]
    public function it_generates_key_parameter_with_correct_type(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                ],
                operations: ['read'],
            ),
        ];

        $spec = OpenApiGenerator::generate($entityTypes);

        $singlePath = $spec['paths']['/Products({Id})'];
        $keyParam = $singlePath['get']['parameters'][0];
        $this->assertSame('Id', $keyParam['name']);
        $this->assertSame('path', $keyParam['in']);
        $this->assertTrue($keyParam['required']);
        $this->assertSame(['type' => 'integer', 'format' => 'int64'], $keyParam['schema']);
    }

    /**
     * Find a parameter by name from the parameters array.
     *
     * @param list<array<string, mixed>> $parameters
     * @return array<string, mixed>
     */
    private function findParameter(array $parameters, string $name): array
    {
        foreach ($parameters as $param) {
            if ($param['name'] === $name) {
                return $param;
            }
        }

        $this->fail("Parameter '{$name}' not found.");
    }
}
