<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Projector\ProductCollectionType;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeNameChangedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class ProductCollectionTypeNameChangedEventProjector
{
    private const TABLE = 'collection_type';

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
     * @param ProductCollectionTypeNameChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductCollectionTypeNameChangedEvent $event): void
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
