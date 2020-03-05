<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\CategoryTree;

use Doctrine\DBAL\Connection;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoriesChangedEvent;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportTree;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class CategoryTreeCategoriesChangedEventProjector
{
    private const TABLE_TREE = 'exporter.tree';

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
     * @param CategoryTreeCategoriesChangedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(CategoryTreeCategoriesChangedEvent $event): void
    {
        $id = Uuid::fromString($event->getAggregateId()->getValue());
        $tree = new ExportTree(
            $id,
            $event->getCategories()
        );


        $this->connection->update(
            self::TABLE_TREE,
            [
                'data' => $this->serializer->serialize($tree, 'json'),
            ],
            [
                'id' => $tree->getId()->toString(),
            ]
        );
    }
}
