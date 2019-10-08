<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Condition\Domain\Event\ConditionSetDeletedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class ConditionSetDeletedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'condition_set';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof ConditionSetDeletedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ConditionSetDeletedEvent) {
            throw new UnsupportedEventException($event, ConditionSetDeletedEvent::class);
        }

        $this->connection->delete(
            self::TABLE,
            [
                'id' => $aggregateId->getValue(),
            ]
        );
    }
}
