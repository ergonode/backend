<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Group;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Group\AttributeGroupCreatedEvent;
use Ergonode\Attribute\Domain\Event\Group\AttributeGroupDeletedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class AttributeGroupDeletedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'attribute_group';

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
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof AttributeGroupDeletedEvent;
    }

    /**
     * @param AbstractId                                      $aggregateId
     * @param DomainEventInterface|AttributeGroupCreatedEvent $event
     *
     * @throws UnsupportedEventException
     * @throws DBALException
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, AttributeGroupDeletedEvent::class);
        }

        $this->connection->delete(
            self::TABLE,
            [
                'id' => $aggregateId->getValue(),
            ]
        );
    }
}
