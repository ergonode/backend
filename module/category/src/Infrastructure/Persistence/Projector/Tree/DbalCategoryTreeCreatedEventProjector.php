<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Persistence\Projector\Tree;

use Doctrine\DBAL\Connection;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCreatedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalCategoryTreeCreatedEventProjector
{
    protected const TABLE = 'category_tree';

    protected Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    public function __invoke(CategoryTreeCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'code' => $event->getCode(),
                'name' => $this->serializer->serialize($event->getName()->getTranslations()),
            ]
        );
    }
}
