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
}
