<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Account\Domain\Event\User\UserPasswordChangedEvent;

class Version20210728123000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $eventId = $this->connection->executeQuery(
            'SELECT id FROM event_store_event WHERE event_class = :class',
            [
                'class' => UserPasswordChangedEvent::class,
            ]
        )->fetchOne();

        $aggregates = $this->connection->executeQuery(
            'SELECT aggregate_id, payload->\'password\' as password 
                 FROM event_store WHERE event_id = :id AND sequence = 2',
            [
                'id' => $eventId,
            ]
        )->fetchAllAssociative();

        foreach ($aggregates as $aggregate) {
            $this->addSql(
                'UPDATE event_store SET payload = jsonb_set(payload, \'{password}\',:password::JSONB) 
                     WHERE aggregate_id = :id and sequence = 1',
                [
                    'password' => $aggregate['password'],
                    'id' => $aggregate['aggregate_id'],
                ]
            );
        }
    }
}
