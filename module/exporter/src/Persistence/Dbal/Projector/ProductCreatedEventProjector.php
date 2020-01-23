<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Exporter\Domain\Factory\SimpleProductFactory;
use Ergonode\Product\Domain\Event\ProductCreatedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class ProductCreatedEventProjector
{
    private const TABLE_PRODUCT = 'exporter.product';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * ProductCreatedEventProjector constructor.
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param ProductCreatedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(ProductCreatedEvent $event): void
    {
        $product = SimpleProductFactory::createFromEvent(
            $event->getAggregateId()->getValue(),
            $event->getSku()->getValue(),
            $event->getCategories(),
            $event->getAttributes()
        );

        $this->connection->insert(
            self::TABLE_PRODUCT,
            [
                'id' => $product->getId(),
                'data' => $this->serializer->serialize($product, 'json'),
            ]
        );
    }
}
