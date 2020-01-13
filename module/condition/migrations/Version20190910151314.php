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
final class Version20190910151314 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE condition_set (
                id UUID NOT NULL,
                code VARCHAR(100) NOT NULL,
                conditions JSONB NOT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE index condition_set_code_uindex ON condition_set (code)');

        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'CONDITION_CREATE', 'Condition']
        );
        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'CONDITION_READ', 'Condition']
        );
        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'CONDITION_UPDATE', 'Condition']
        );
        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'CONDITION_DELETE', 'Condition']
        );

        $this->createEventStoreEvents([
            'Ergonode\Condition\Domain\Event\ConditionSetCreatedEvent' => 'Condition set created',
            'Ergonode\Condition\Domain\Event\ConditionSetDeletedEvent' => 'Condition set deleted',
            'Ergonode\Condition\Domain\Event\ConditionSetDescriptionChangedEvent' =>
                'Condition set description changed',
            'Ergonode\Condition\Domain\Event\ConditionSetNameChangedEvent' => 'Condition set name changed',
            'Ergonode\Condition\Domain\Event\ConditionSetConditionsChangedEvent' => 'Condition set conditions changed',
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
