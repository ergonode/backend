<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Projector\CategoryTree;

use Doctrine\DBAL\Connection;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeDeletedEvent;

/**
 */
class CategoryTreeDeletedEventProjector
{
    private const TABLE_TREE = 'exporter.tree';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param CategoryTreeDeletedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function __invoke(CategoryTreeDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE_TREE,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
