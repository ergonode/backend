<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Condition\Domain\Event\ConditionSetConditionsChangedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class ConditionSetConditionsChangedEventProjector
{
    private const TABLE = 'condition_set';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param ConditionSetConditionsChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ConditionSetConditionsChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'conditions' => $this->serializer->serialize($event->getTo(), 'json'),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
