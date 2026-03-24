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
