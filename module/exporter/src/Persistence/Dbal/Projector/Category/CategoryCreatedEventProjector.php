<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\Category;

use Doctrine\DBAL\Connection;
use Ergonode\Category\Domain\Event\CategoryCreatedEvent;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class CategoryCreatedEventProjector
{
    private const TABLE_CATEGORY = 'exporter.category';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param CategoryCreatedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(CategoryCreatedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $category = new ExportCategory(
            $id,
            $event->getCode()->getValue(),
            $event->getName()
        );

        $this->connection->insert(
            self::TABLE_CATEGORY,
            [
                'id' => $category->getId(),
                'code' => $category->getCode(),
                'data' => $this->serializer->serialize($category, 'json'),
            ]
        );
    }
}
