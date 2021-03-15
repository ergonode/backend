<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20200127083123 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');

        $this->addSql(
            'CREATE TABLE product_collection_type(
                    id uuid NOT NULL,
                    code VARCHAR(255) DEFAULT NULL, 
                    name JSONB NOT NULL, 
                    PRIMARY KEY (id)
                 )'
        );

        $this->addSql(
            'CREATE TABLE product_collection(
                    id uuid NOT NULL,
                    code VARCHAR(255) DEFAULT NULL, 
                    name JSONB NOT NULL, 
                    description JSONB NOT NULL, 
                    type_id uuid NOT NULL,
                    created_at timestamp with time zone DEFAULT NULL,
                    edited_at timestamp with time zone DEFAULT NULL,
                    PRIMARY KEY (id)
                 )'
        );
        $this->addSql(
            'ALTER TABLE product_collection
                    ADD CONSTRAINT product_collection_product_collection_type_fk FOREIGN KEY (type_id) 
                    REFERENCES product_collection_type(id) ON DELETE RESTRICT ON UPDATE CASCADE'
        );

        $this->addSql(
            'CREATE TABLE product_collection_element(
                    product_collection_id uuid NOT NULL,
                    product_id uuid NOT NULL,
                    visible BOOLEAN NOT NULL,
                    created_at timestamp with time zone NOT NULL,
                    PRIMARY KEY (product_collection_id, product_id)
                 )'
        );
        $this->addSql(
            'ALTER TABLE product_collection_element
                    ADD CONSTRAINT product_collection_element_product_collection_fk FOREIGN KEY (product_collection_id) 
                    REFERENCES product_collection(id) ON DELETE CASCADE ON UPDATE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE product_collection_element
                    ADD CONSTRAINT product_collection_element_product_fk FOREIGN KEY (product_id) 
                    REFERENCES product(id) ON DELETE CASCADE ON UPDATE CASCADE'
        );

        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Product Collections']);
        $this->createPrivileges([
            'PRODUCT_COLLECTION_CREATE' => 'Product Collections',
            'PRODUCT_COLLECTION_READ' => 'Product Collections',
            'PRODUCT_COLLECTION_UPDATE' => 'Product Collections',
            'PRODUCT_COLLECTION_DELETE' => 'Product Collections',
        ]);

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
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionDescriptionChangedEvent'
            => 'Product collection description changed',
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeCreatedEvent'
            => 'Product collection type created',
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeIdChangedEvent'
            => 'Product collection type id changed',
            'Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeDeletedEvent'
            => 'Product collection type deleted',
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
            $this->addSql(
                'INSERT INTO event_store_event (id, event_class, translation_key) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $class, $translation]
            );
        }
    }

    /**
     * @param array $collection
     *
     * @throws \Exception
     */
    private function createPrivileges(array $collection): void
    {
        foreach ($collection as $code => $area) {
            $this->addSql(
                'INSERT INTO privileges (id, code, area) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $code,  $area, ]
            );
        }
    }
}
