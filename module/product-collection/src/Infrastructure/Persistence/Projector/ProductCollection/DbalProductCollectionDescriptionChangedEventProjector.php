<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Persistence\Projector\ProductCollection;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Types;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionDescriptionChangedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalProductCollectionDescriptionChangedEventProjector
{
    private const TABLE = 'product_collection';

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
    public function __invoke(ProductCollectionDescriptionChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'description' => $this->serializer->serialize($event->getTo()->getTranslations()),
                'edited_at' => $event->getEditedAt(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ],
            [
                'edited_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
