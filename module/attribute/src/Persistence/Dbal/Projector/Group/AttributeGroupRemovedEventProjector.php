<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Group;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\AttributeGroupRemovedEvent;
use Ergonode\EventSourcing\Infrastructure\Exception\ProjectorException;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class AttributeGroupRemovedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'attribute_group_attribute';

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
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof AttributeGroupRemovedEvent;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws ProjectorException
     * @throws UnsupportedEventException
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof AttributeGroupRemovedEvent) {
            throw new UnsupportedEventException($event, AttributeGroupRemovedEvent::class);
        }

        try {
            $this->connection->beginTransaction();
            $this->connection->delete(
                self::TABLE,
                [
                    'attribute_id' => $aggregateId->getValue(),
                    'attribute_group_id' => $event->getGroupId()->getValue(),
                ]
            );
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw new ProjectorException($event, $exception);
        }
    }
}
