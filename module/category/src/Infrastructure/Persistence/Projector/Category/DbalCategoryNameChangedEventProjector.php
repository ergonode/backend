<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Persistence\Projector\Category;

use Doctrine\DBAL\Connection;
use Ergonode\Category\Domain\Event\CategoryNameChangedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

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

    public function __invoke(CategoryNameChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'name' => $this->serializer->serialize($event->getTo()->getTranslations()),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
