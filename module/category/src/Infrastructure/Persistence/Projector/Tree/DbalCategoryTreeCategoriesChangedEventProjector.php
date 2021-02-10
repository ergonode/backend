<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Persistence\Projector\Tree;

use Doctrine\DBAL\Connection;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoriesChangedEvent;

class DbalCategoryTreeCategoriesChangedEventProjector
{
    protected const TABLE = 'category_tree_category';

    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(CategoryTreeCategoriesChangedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'category_tree_id' => $event->getAggregateId()->getValue(),
            ]
        );

        foreach ($event->getCategories() as $node) {
            $this->connection->insert(
                self::TABLE,
                [
                    'category_tree_id' => $event->getAggregateId()->getValue(),
                    'category_id' => $node->getCategoryId()->getValue(),
                ]
            );
        }
    }
}
