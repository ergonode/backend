<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Group;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\AttributeGroupRemovedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
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
     * {@inheritDoc}
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof AttributeGroupRemovedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof AttributeGroupRemovedEvent) {
            throw new UnsupportedEventException($event, AttributeGroupRemovedEvent::class);
        }

        $this->connection->delete(
            self::TABLE,
            [
                'attribute_id' => $aggregateId->getValue(),
                'attribute_group_id' => $event->getGroupId()->getValue(),
            ]
        );
    }
}
