<?php

declare(strict_types=1);

namespace NovaBytes\OData\Metadata;

use XMLWriter;

/**
 * Generates OData v4 CSDL (Common Schema Definition Language) XML from entity type metadata.
 */
class CsdlGenerator
{
    /**
     * Generate a CSDL XML document for the given entity types.
     *
     * @param string $namespace The schema namespace (e.g., "Default").
     * @param list<EntityType> $entityTypes The entity types to include.
     * @return string The CSDL XML document.
     */
    public static function generate(string $namespace, array $entityTypes): string
    {
        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString('  ');

        $writer->startDocument('1.0', 'utf-8');

        $writer->startElement('edmx:Edmx');
        $writer->writeAttribute('Version', '4.0');
        $writer->writeAttribute('xmlns:edmx', 'http://docs.oasis-open.org/odata/ns/edmx');

        $writer->startElement('edmx:DataServices');
        $writer->startElement('Schema');
        $writer->writeAttribute('Namespace', $namespace);
        $writer->writeAttribute('xmlns', 'http://docs.oasis-open.org/odata/ns/edm');

        self::writeEntityTypes($writer, $namespace, $entityTypes);
        self::writeEntityContainer($writer, $namespace, $entityTypes);

        $writer->endElement(); // Schema
        $writer->endElement(); // edmx:DataServices
        $writer->endElement(); // edmx:Edmx

        $writer->endDocument();

        return $writer->outputMemory();
    }

    /**
     * Write EntityType elements for each entity type.
     *
     * @param list<EntityType> $entityTypes
     */
    private static function writeEntityTypes(XMLWriter $writer, string $namespace, array $entityTypes): void
    {
        foreach ($entityTypes as $entityType) {
            $writer->startElement('EntityType');
            $writer->writeAttribute('Name', $entityType->name);

            $writer->startElement('Key');
            $writer->startElement('PropertyRef');
            $writer->writeAttribute('Name', $entityType->keyProperty);
            $writer->endElement(); // PropertyRef
            $writer->endElement(); // Key

            foreach ($entityType->properties as $property) {
                $writer->startElement('Property');
                $writer->writeAttribute('Name', $property->name);
                $writer->writeAttribute('Type', $property->edmType);

                if ($property->nullable) {
                    $writer->writeAttribute('Nullable', 'true');
                } else {
                    $writer->writeAttribute('Nullable', 'false');
                }

                $writer->endElement(); // Property
            }

            foreach ($entityType->navigationProperties as $navProperty) {
                $writer->startElement('NavigationProperty');
                $writer->writeAttribute('Name', $navProperty->name);

                if ($navProperty->isCollection) {
                    $writer->writeAttribute('Type', "Collection({$namespace}.{$navProperty->targetEntityType})");
                } else {
                    $writer->writeAttribute('Type', "{$namespace}.{$navProperty->targetEntityType}");
                }

                $writer->endElement(); // NavigationProperty
            }

            $writer->endElement(); // EntityType
        }
    }

    /**
     * Write the EntityContainer element with EntitySet entries.
     *
     * @param list<EntityType> $entityTypes
     */
    private static function writeEntityContainer(XMLWriter $writer, string $namespace, array $entityTypes): void
    {
        $writer->startElement('EntityContainer');
        $writer->writeAttribute('Name', 'DefaultContainer');

        foreach ($entityTypes as $entityType) {
            $writer->startElement('EntitySet');
            $writer->writeAttribute('Name', $entityType->entitySetName);
            $writer->writeAttribute('EntityType', "{$namespace}.{$entityType->name}");
            $writer->endElement(); // EntitySet
        }

        $writer->endElement(); // EntityContainer
    }
}
