<?php

declare(strict_types=1);

namespace NovaBytes\OData\Tests\Metadata;

use NovaBytes\OData\Metadata\CsdlGenerator;
use NovaBytes\OData\Metadata\EntityType;
use NovaBytes\OData\Metadata\NavigationPropertyMetadata;
use NovaBytes\OData\Metadata\PropertyMetadata;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CsdlGeneratorTest extends TestCase
{
    #[Test]
    public function it_generates_valid_csdl_xml(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                    new PropertyMetadata('Name', 'Edm.String', nullable: false),
                    new PropertyMetadata('Price', 'Edm.Decimal', nullable: false),
                ],
                navigationProperties: [
                    new NavigationPropertyMetadata('Category', 'Category', isCollection: false),
                    new NavigationPropertyMetadata('Reviews', 'Review', isCollection: true),
                ],
            ),
        ];

        $xml = CsdlGenerator::generate('Default', $entityTypes);

        $doc = new \DOMDocument();
        $this->assertTrue($doc->loadXML($xml), 'Generated CSDL is not valid XML.');

        $this->assertStringContainsString('Version="4.0"', $xml);
        $this->assertStringContainsString('Namespace="Default"', $xml);
        $this->assertStringContainsString('Name="Product"', $xml);
        $this->assertStringContainsString('Name="Id" Type="Edm.Int64" Nullable="false"', $xml);
        $this->assertStringContainsString('Name="Name" Type="Edm.String" Nullable="false"', $xml);
        $this->assertStringContainsString('Name="Price" Type="Edm.Decimal" Nullable="false"', $xml);
        $this->assertStringContainsString('Name="Category" Type="Default.Category"', $xml);
        $this->assertStringContainsString('Name="Reviews" Type="Collection(Default.Review)"', $xml);
        $this->assertStringContainsString('Name="Products" EntityType="Default.Product"', $xml);
    }

    #[Test]
    public function it_generates_nullable_properties(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Review',
                entitySetName: 'Reviews',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                    new PropertyMetadata('Body', 'Edm.String', nullable: true),
                ],
            ),
        ];

        $xml = CsdlGenerator::generate('Default', $entityTypes);

        $this->assertStringContainsString('Name="Body" Type="Edm.String" Nullable="true"', $xml);
    }

    #[Test]
    public function it_generates_multiple_entity_types(): void
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
            new EntityType(
                name: 'Category',
                entitySetName: 'Categories',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                ],
            ),
        ];

        $xml = CsdlGenerator::generate('MyApp', $entityTypes);

        $this->assertStringContainsString('Namespace="MyApp"', $xml);
        $this->assertStringContainsString('EntityType Name="Product"', $xml);
        $this->assertStringContainsString('EntityType Name="Category"', $xml);
        $this->assertStringContainsString('EntitySet Name="Products" EntityType="MyApp.Product"', $xml);
        $this->assertStringContainsString('EntitySet Name="Categories" EntityType="MyApp.Category"', $xml);
    }

    #[Test]
    public function it_generates_empty_schema_with_no_entity_types(): void
    {
        $xml = CsdlGenerator::generate('Default', []);

        $doc = new \DOMDocument();
        $this->assertTrue($doc->loadXML($xml));
        $this->assertStringContainsString('DefaultContainer', $xml);
    }

    #[Test]
    public function it_includes_key_property_ref(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'ProductId',
                properties: [
                    new PropertyMetadata('ProductId', 'Edm.Int32', nullable: false),
                ],
            ),
        ];

        $xml = CsdlGenerator::generate('Default', $entityTypes);

        $this->assertStringContainsString('PropertyRef Name="ProductId"', $xml);
    }

    #[Test]
    public function it_includes_capability_annotations_for_crud_operations(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                ],
                operations: ['read', 'create', 'update', 'delete'],
            ),
        ];

        $xml = CsdlGenerator::generate('Default', $entityTypes);

        $doc = new \DOMDocument();
        $this->assertTrue($doc->loadXML($xml), 'Generated CSDL is not valid XML.');

        $this->assertStringContainsString('Org.OData.Capabilities.V1.InsertRestrictions', $xml);
        $this->assertStringContainsString('Org.OData.Capabilities.V1.UpdateRestrictions', $xml);
        $this->assertStringContainsString('Org.OData.Capabilities.V1.DeleteRestrictions', $xml);
    }

    #[Test]
    public function it_includes_capabilities_reference_when_crud_operations_exist(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                ],
                operations: ['read', 'create'],
            ),
        ];

        $xml = CsdlGenerator::generate('Default', $entityTypes);

        $this->assertStringContainsString('edmx:Reference', $xml);
        $this->assertStringContainsString('Org.OData.Capabilities.V1', $xml);
    }

    #[Test]
    public function it_omits_capabilities_reference_when_only_read(): void
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

        $xml = CsdlGenerator::generate('Default', $entityTypes);

        $this->assertStringNotContainsString('edmx:Reference', $xml);
        $this->assertStringNotContainsString('InsertRestrictions', $xml);
    }

    #[Test]
    public function it_writes_correct_boolean_values_for_restrictions(): void
    {
        $entityTypes = [
            new EntityType(
                name: 'Product',
                entitySetName: 'Products',
                keyProperty: 'Id',
                properties: [
                    new PropertyMetadata('Id', 'Edm.Int64', nullable: false),
                ],
                operations: ['read', 'create'],
            ),
        ];

        $xml = CsdlGenerator::generate('Default', $entityTypes);

        // Create is allowed
        $this->assertStringContainsString('Property="Insertable" Bool="true"', $xml);
        // Update and delete are not allowed
        $this->assertStringContainsString('Property="Updatable" Bool="false"', $xml);
        $this->assertStringContainsString('Property="Deletable" Bool="false"', $xml);
    }
}
