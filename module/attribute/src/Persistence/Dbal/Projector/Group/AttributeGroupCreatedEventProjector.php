<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Group;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Event\Group\AttributeGroupCreatedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use JMS\Serializer\SerializerInterface;

/**
 */
class AttributeGroupCreatedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'attribute_group';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SerializerInterface
     */
    private $serializer;

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
     * {@inheritDoc}
     */
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof AttributeGroupCreatedEvent;
    }

    /**
     * @param AbstractId                                      $aggregateId
     * @param DomainEventInterface|AttributeGroupCreatedEvent $event
     *
     * @throws UnsupportedEventException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, AttributeGroupCreatedEvent::class);
        }

        $this->connection->insert(
            self::TABLE,
            [
                'id' => $aggregateId->getValue(),
                'code' => $event->getCode(),
                'name' => $this->serializer->serialize($event->getName(), 'json'),
            ]
        );
    }
}
