<?php

declare(strict_types=1);

namespace NovaBytes\OData\Metadata;

use XMLWriter;

/**
 * Generates OData v4 CSDL (Common Schema Definition Language) XML from entity type metadata.
 */
class CsdlGenerator
{
    private const CAPABILITIES_NAMESPACE = 'Org.OData.Capabilities.V1';

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

        if (self::hasNonReadOperations($entityTypes)) {
            self::writeCapabilitiesReference($writer);
        }

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
     * Write the Reference element for the OData Capabilities vocabulary.
     */
    private static function writeCapabilitiesReference(XMLWriter $writer): void
    {
        $writer->startElement('edmx:Reference');
        $writer->writeAttribute('Uri', 'https://docs.oasis-open.org/odata/odata-vocabularies/v4.0/vocabularies/Org.OData.Capabilities.V1.xml');

        $writer->startElement('edmx:Include');
        $writer->writeAttribute('Namespace', self::CAPABILITIES_NAMESPACE);
        $writer->endElement(); // edmx:Include

        $writer->endElement(); // edmx:Reference
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
     * Write the EntityContainer element with EntitySet entries and capability annotations.
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

            self::writeCapabilityAnnotations($writer, $entityType);

            $writer->endElement(); // EntitySet
        }

        $writer->endElement(); // EntityContainer
    }

    /**
     * Write capability restriction annotations for an entity set.
     */
    private static function writeCapabilityAnnotations(XMLWriter $writer, EntityType $entityType): void
    {
        $isInsertable = in_array('create', $entityType->operations, true);
        $isUpdatable = in_array('update', $entityType->operations, true);
        $isDeletable = in_array('delete', $entityType->operations, true);

        // Only write annotations if there are non-default capabilities
        if (!$isInsertable && !$isUpdatable && !$isDeletable) {
            return;
        }

        self::writeRestrictionAnnotation($writer, 'InsertRestrictions', 'Insertable', $isInsertable);
        self::writeRestrictionAnnotation($writer, 'UpdateRestrictions', 'Updatable', $isUpdatable);
        self::writeRestrictionAnnotation($writer, 'DeleteRestrictions', 'Deletable', $isDeletable);
    }

    /**
     * Write a single capability restriction annotation.
     */
    private static function writeRestrictionAnnotation(XMLWriter $writer, string $term, string $property, bool $value): void
    {
        $writer->startElement('Annotation');
        $writer->writeAttribute('Term', self::CAPABILITIES_NAMESPACE . ".{$term}");

        $writer->startElement('Record');

        $writer->startElement('PropertyValue');
        $writer->writeAttribute('Property', $property);
        $writer->writeAttribute('Bool', $value ? 'true' : 'false');
        $writer->endElement(); // PropertyValue

        $writer->endElement(); // Record

        $writer->endElement(); // Annotation
    }

    /**
     * Check whether any entity type has non-read operations.
     *
     * @param list<EntityType> $entityTypes
     */
    private static function hasNonReadOperations(array $entityTypes): bool
    {
        foreach ($entityTypes as $entityType) {
            foreach ($entityType->operations as $operation) {
                if ($operation !== 'read') {
                    return true;
                }
            }
        }

        return false;
    }
}
