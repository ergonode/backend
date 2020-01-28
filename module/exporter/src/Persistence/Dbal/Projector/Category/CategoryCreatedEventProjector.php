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
use Ergonode\Exporter\Domain\Entity\Category;
use JMS\Serializer\SerializerInterface;

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
     * CategoryCreatedEventProjector constructor.
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
        $category = new Category(
            $event->getAggregateId()->getValue(),
            $event->getCode()->getValue(),
            $event->getName()
        );

        $this->connection->insert(
            self::TABLE_CATEGORY,
            [
                'id' => $category->getId(),
                'data' => $this->serializer->serialize($category, 'json'),
            ]
        );
    }
}
