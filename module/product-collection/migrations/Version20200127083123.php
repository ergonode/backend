<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20200127083123 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE collection(
                    id uuid NOT NULL,
                    code VARCHAR(255) DEFAULT NULL, 
                    name JSONB NOT NULL, 
                    type_id uuid NOT NULL,
                    PRIMARY KEY (id)
                 )'
        );

        $this->addSql(
            'CREATE TABLE collection_element(
                    product_collection_id uuid NOT NULL,
                    product_id uuid NOT NULL,
                    visible BOOLEAN NOT NULL,
                    PRIMARY KEY (product_collection_id, product_id)
                 )'
        );

        $this->addSql(
            'CREATE TABLE collection_type(
                    id uuid NOT NULL,
                    code VARCHAR(255) DEFAULT NULL, 
                    name JSONB NOT NULL, 
                    PRIMARY KEY (id)
                 )'
        );

        $this->createEventStoreEvents([
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionCreatedEvent'
            => 'Product collection created',
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionElementAddedEvent'
            => 'Product collection element added',
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionElementRemovedEvent'
            => 'Product collection element removed',
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionElementVisibleChangedEvent'
            => 'Product collection element visible changed',
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionNameChangedEvent'
            => 'Product collection name changed',
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeCreatedEvent'
            => 'Product collection type created',
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeIdChangedEvent'
            => 'Product collection type id changed',
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeNameChangedEvent'
            => 'Product collection type name changed',
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionDeletedEvent'
            => 'Product collection removed',
        ]);
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
     */
    private function createEventStoreEvents(array $collection): void
    {
        foreach ($collection as $class => $translation) {
            $this->connection->insert('event_store_event', [
                'id' => Uuid::uuid4()->toString(),
                'event_class' => $class,
                'translation_key' => $translation,
            ]);
        }
    }
}
