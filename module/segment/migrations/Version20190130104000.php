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
                name JSON NOT NULL,
                description JSON NOT NULL,
                status VARCHAR(32) NOT NULL,
                condition_set_id UUID DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'SEGMENT_CREATE', 'Segment']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'SEGMENT_READ', 'Segment']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'SEGMENT_UPDATE', 'Segment']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'SEGMENT_DELETE', 'Segment']);

        $this->createEventStoreEvents([
            'Ergonode\Segment\Domain\Event\SegmentCreatedEvent' => 'Segment created',
            'Ergonode\Segment\Domain\Event\SegmentDescriptionChangedEvent' => 'Segment description changed',
            'Ergonode\Segment\Domain\Event\SegmentNameChangedEvent' => 'Segment name changed',
            'Ergonode\Segment\Domain\Event\SegmentSpecificationAddedEvent' => 'Segment specification added',
            'Ergonode\Segment\Domain\Event\SegmentStatusChangedEvent' => 'Segment status changed',
            'Ergonode\Segment\Domain\Event\SegmentDeletedEvent' => 'Segment deleted',
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
