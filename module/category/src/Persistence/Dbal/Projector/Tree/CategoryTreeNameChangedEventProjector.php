<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Persistence\Dbal\Projector\Tree;

use Doctrine\DBAL\Connection;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeNameChangedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class CategoryTreeNameChangedEventProjector
{
    private const TABLE = 'tree';

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
