<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Condition\Domain\Event\ConditionSetConditionsChangedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalConditionSetConditionsChangedEventProjector
{
    private const TABLE = 'condition_set';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(ConditionSetConditionsChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'conditions' => $this->serializer->serialize($event->getTo()),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
