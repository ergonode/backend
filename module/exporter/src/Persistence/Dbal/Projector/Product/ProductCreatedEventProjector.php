<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\Product;

use Doctrine\DBAL\Connection;
use Ergonode\Exporter\Domain\Factory\SimpleProductFactory;
use Ergonode\Exporter\Domain\Provider\ProductProvider;
use Ergonode\Product\Domain\Event\ProductCreatedEvent;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

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
     * @var ProductProvider
     */
    private ProductProvider $productProvider;

    /**
     * ProductCreatedEventProjector constructor.
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     * @param ProductProvider     $productProvider
     */
    public function __construct(
        Connection $connection,
        SerializerInterface $serializer,
        ProductProvider $productProvider
    ) {
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->productProvider = $productProvider;
    }

    /**
     * @param ProductCreatedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(ProductCreatedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $product = $this->productProvider->createFromEvent(
            $id,
            $event->getSku()->getValue(),
            $event->getCategories(),
            $event->getAttributes()
        );

        $this->connection->insert(
            self::TABLE_PRODUCT,
            [
                'id' => $product->getId()->toString(),
                'data' => $this->serializer->serialize($product, 'json'),
            ]
        );
    }
}
