<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Persistence\Projector\Tree;

use Doctrine\DBAL\Connection;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeDeletedEvent;

class DbalCategoryTreeDeletedEventProjector
{
    private const TABLE = 'category_tree';
    private const TABLE_CATEGORY = 'category_tree_category';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(CategoryTreeDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE_CATEGORY,
            [
                'category_tree_id' => $event->getAggregateId()->getValue(),
            ]
        );

        $this->connection->delete(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
