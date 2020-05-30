<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20180619083830 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE IF NOT EXISTS product (
                id UUID NOT NULL,
                index SERIAL,
                sku VARCHAR(128) NOT NULL,
                type VARCHAR(128) NOT NULL,
                template_id UUID NOT NULL,
                created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX product_sku_key ON product USING btree(sku)');

        $this->addSql('
            CREATE TABLE IF NOT EXISTS product_children (
                product_id UUID NOT NULL,
                child_id UUID NOT NULL,                
                PRIMARY KEY(product_id, child_id)
            )
        ');

        $this->addSql(
            'ALTER TABLE product_children 
                    ADD CONSTRAINT product_children_product_id_fk
                        FOREIGN KEY (product_id) REFERENCES public.product on update cascade on delete cascade'
        );

        $this->addSql(
            'ALTER TABLE product_children
                    ADD CONSTRAINT product_children_child_id_fk
                        FOREIGN KEY (child_id) REFERENCES public.product on update cascade on delete restrict'
        );

        $this->addSql('
            CREATE TABLE IF NOT EXISTS product_binding (
                product_id UUID NOT NULL,
                attribute_id UUID NOT NULL,                
                PRIMARY KEY(product_id, attribute_id)
            )
        ');

        $this->addSql(
            'ALTER TABLE product_binding 
                    ADD CONSTRAINT product_binding_product_id_fk
                        FOREIGN KEY (product_id) REFERENCES public.product on update cascade on delete cascade'
        );

        $this->addSql(
            'ALTER TABLE product_binding
                    ADD CONSTRAINT product_binding_attribute_id_fk
                        FOREIGN KEY (attribute_id) REFERENCES public.attribute on update cascade on delete restrict'
        );

        $this->addSql('
            CREATE TABLE product_value
                (
                    product_id UUID NOT NULL,
                    attribute_id UUID NOT NULL,
                    value_id UUID NOT NULL,
                    PRIMARY KEY(product_id, attribute_id, value_id)
                )');
        $this->addSql(
            'ALTER TABLE product_value 
                    ADD CONSTRAINT product_value_product_id_fk
                        FOREIGN KEY (product_id) REFERENCES public.product on update cascade on delete cascade'
        );
        $this->addSql('
                ALTER TABLE product_value
                    ADD CONSTRAINT product_value_attribute_id_fk
                        FOREIGN KEY (attribute_id) REFERENCES public.attribute on update cascade on delete cascade');

        $this->addSql('
                CREATE TABLE product_category_product
                    (
                        category_id UUID NOT NULL,
                        product_id UUID NOT NULL,
                        PRIMARY KEY(category_id, product_id)
                    )');
        $this->addSql('
            ALTER TABLE product_category_product
                ADD CONSTRAINT product_category_product_product_id_fk
                    FOREIGN KEY (product_id) REFERENCES public.product on update cascade on delete cascade');
        $this->addSql('
            ALTER TABLE product_category_product
                ADD CONSTRAINT product_category_product_category_id_fk
                    FOREIGN KEY (category_id) REFERENCES public.category on update cascade on delete cascade');

        $this->addSql('CREATE TABLE product_status (code VARCHAR(32), name VARCHAR(64), PRIMARY KEY(code))');
        $this->addSql('INSERT INTO product_status (code, name) VALUES(?, ?)', ['DRAFT', 'Draft']);
        $this->addSql('INSERT INTO product_status (code, name) VALUES(?, ?)', ['ACCEPTED', 'Accepted']);
        $this->addSql('INSERT INTO product_status (code, name) VALUES(?, ?)', ['TO_ACCEPTED', 'To accept']);
        $this->addSql('INSERT INTO product_status (code, name) VALUES(?, ?)', ['TO_CORRECT', 'To correct']);

        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'PRODUCT_CREATE', 'Product']
        );
        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'PRODUCT_READ', 'Product']
        );
        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'PRODUCT_UPDATE', 'Product']
        );
        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'PRODUCT_DELETE', 'Product']
        );

        $this->createEventStoreEvents([
            'Ergonode\Product\Domain\Event\ProductAddedToCategoryEvent' => 'Product added to category',
            'Ergonode\Product\Domain\Event\ProductCreatedEvent' => 'Product created',
            'Ergonode\Product\Domain\Event\ProductRemovedFromCategoryEvent' => 'Product removed from category',
            'Ergonode\Product\Domain\Event\ProductValueAddedEvent' => 'Product attribute value added',
            'Ergonode\Product\Domain\Event\ProductValueChangedEvent' => 'Product attribute value changed',
            'Ergonode\Product\Domain\Event\ProductValueRemovedEvent' => 'Product attribute value removed',
            'Ergonode\Product\Domain\Event\ProductDeletedEvent' => 'Product deleted',
            'Ergonode\Product\Domain\Event\Bind\BindAddedToProductEvent' => 'Attribute binded',
            'Ergonode\Product\Domain\Event\Bind\BindRemovedFromProductEvent' => 'Attribute unbinded',
            'Ergonode\Product\Domain\Event\Relation\ChildAddedToProductEvent' => 'Product relation added',
            'Ergonode\Product\Domain\Event\Relation\ChildRemovedFromProductEvent' => 'Product relation removed',
        ]);
    }

    /**
     * @param array $collection
     *
     * @throws \Doctrine\DBAL\DBALException
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
