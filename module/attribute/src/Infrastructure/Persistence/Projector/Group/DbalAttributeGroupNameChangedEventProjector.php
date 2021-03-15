<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Group;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Event\Group\AttributeGroupNameChangedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalAttributeGroupNameChangedEventProjector
{
    private const TABLE = 'attribute_group';

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
    public function __invoke(AttributeGroupNameChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'name' => $this->serializer->serialize($event->getTo()),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
