<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

final class Version20190910151314 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE condition_set (
                id UUID NOT NULL,
                conditions JSONB NOT NULL,
                PRIMARY KEY(id)
            )
        ');

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
            $this->addSql(
                'INSERT INTO event_store_event (id, event_class, translation_key) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $class, $translation]
            );
        }
    }
}
