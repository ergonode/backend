<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeCreatedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use JMS\Serializer\SerializerInterface;

/**
 */
class CategoryTreeCreatedEventProjector implements DomainEventProjectorInterface
{
    protected const TABLE = 'tree';

    /**
     * @var Connection
     */
    protected $connection;

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
        return $event instanceof CategoryTreeCreatedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, CategoryTreeCreatedEvent::class);
        }

        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getId(),
                'code' => $event->getCode(),
                'name' => $this->serializer->serialize($event->getName()->getTranslations(), 'json'),
            ]
        );
    }
}
