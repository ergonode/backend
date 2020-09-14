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
final class Version20190130104000 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE segment (
                id UUID NOT NULL,
                code VARCHAR(100) NOT NULL,
                name JSONB NOT NULL,
                description JSON NOT NULL,
                status VARCHAR(32) NOT NULL,
                condition_set_id UUID DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE index segment_code_uindex ON segment (code)');

        $this->addSql('
            CREATE TABLE segment_product (
                segment_id UUID NOT NULL,
                product_id UUID NOT NULL,      
                calculated_at timestamp without time zone DEFAULT NULL,
                available BOOL DEFAULT false NOT NULL,      
                PRIMARY KEY(segment_id, product_id)
            )
        ');

        $this->addSql('
            ALTER TABLE segment_product
                ADD CONSTRAINT segment_product_segment_id_fk
                    FOREIGN KEY (segment_id) REFERENCES public.segment on delete cascade');

        $this->addSql('
            ALTER TABLE segment_product
                ADD CONSTRAINT segment_product_product_id_fk
                    FOREIGN KEY (product_id) REFERENCES public.product on delete cascade');

        $this->connection->insert('privileges_group', ['area' => 'Segment']);
        $this->createSegmentPrivileges(
            [
                'SEGMENT_CREATE',
                'SEGMENT_READ',
                'SEGMENT_UPDATE',
                'SEGMENT_DELETE',
            ]
        );

        $this->createEventStoreEvents([
            'Ergonode\Segment\Domain\Event\SegmentCreatedEvent' => 'Segment created',
            'Ergonode\Segment\Domain\Event\SegmentDescriptionChangedEvent' => 'Segment description changed',
            'Ergonode\Segment\Domain\Event\SegmentNameChangedEvent' => 'Segment name changed',
            'Ergonode\Segment\Domain\Event\SegmentStatusChangedEvent' => 'Segment status changed',
            'Ergonode\Segment\Domain\Event\SegmentDeletedEvent' => 'Segment deleted',
            'Ergonode\Segment\Domain\Event\SegmentConditionSetChangedEvent' => 'Segment condition set changed',
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

    /**
     * @param array $collection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function createSegmentPrivileges(array $collection): void
    {
        foreach ($collection as $code) {
            $this->connection->insert('privileges', [
                'id' => Uuid::uuid4()->toString(),
                'code' => $code,
                'area' => 'Segment',
            ]);
        }
    }
}
