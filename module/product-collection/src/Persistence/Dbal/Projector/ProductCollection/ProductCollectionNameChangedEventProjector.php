<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Projector\ProductCollection;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionNameChangedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class ProductCollectionNameChangedEventProjector
{
    private const TABLE = 'product_collection';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * ProductCollectionNameChangedEventProjector constructor.
     *
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param ProductCollectionNameChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductCollectionNameChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'name' => $this->serializer->serialize($event->getTo()->getTranslations(), 'json'),
                'edited_at' => $event->getEditedAt()->format('Y-m-d H:i:s'),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
