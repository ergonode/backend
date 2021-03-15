<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

final class Version20190130104000 extends AbstractErgonodeMigration
{
    /**
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
                calculated_at timestamp with time zone DEFAULT NULL,
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

        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Segment']);
        $this->createSegmentPrivileges(
            [
                'SEGMENT_CREATE' => 'Segment',
                'SEGMENT_READ' => 'Segment',
                'SEGMENT_UPDATE' => 'Segment',
                'SEGMENT_DELETE' => 'Segment',
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
            $this->addSql(
                'INSERT INTO event_store_event (id, event_class, translation_key) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $class, $translation]
            );
        }
    }

    /**
     * @param array $collection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function createSegmentPrivileges(array $collection): void
    {
        foreach ($collection as $code => $area) {
            $this->addSql(
                'INSERT INTO privileges (id, code, area) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $code,  $area, ]
            );
        }
    }
}
