<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\CategoryTree\Domain\Event\CategoryTreeNameChangedEvent;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\SerializerInterface;

/**
 */
class CategoryTreeNameChangedEventProjector
{
    private const TABLE = 'tree';

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
        return $event instanceof CategoryTreeNameChangedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(CategoryTreeNameChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'name' => $this->serializer->serialize($event->getTo()->getTranslations(), 'json'),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
