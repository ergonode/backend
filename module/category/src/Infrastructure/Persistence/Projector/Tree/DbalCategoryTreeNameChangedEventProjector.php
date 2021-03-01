<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Persistence\Projector\Tree;

use Doctrine\DBAL\Connection;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeNameChangedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalCategoryTreeNameChangedEventProjector
{
    private const TABLE = 'category_tree';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    public function __invoke(CategoryTreeNameChangedEvent $event): void
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
