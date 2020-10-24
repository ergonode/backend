<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Category\Domain\Event\CategoryNameChangedEvent;
use JMS\Serializer\SerializerInterface;

class DbalCategoryNameChangedEventProjector
{
    private const TABLE = 'category';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
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
