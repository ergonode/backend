<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Category\Domain\Event\CategoryNameChangedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class CategoryNameChangedEventProjector
{
    private const TABLE = 'category';

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
     * @param CategoryNameChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(CategoryNameChangedEvent $event): void
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
