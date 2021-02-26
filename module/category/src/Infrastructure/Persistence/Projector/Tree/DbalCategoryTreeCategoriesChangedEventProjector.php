<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Persistence\Projector\Tree;

use Doctrine\DBAL\Connection;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeCategoriesChangedEvent;
use Ergonode\Category\Domain\ValueObject\Node;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;

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
            $this->persistNode($event->getAggregateId(), $node);
        }
    }

    private function persistNode(CategoryTreeId $id, Node $node): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'category_tree_id' => $id->getValue(),
                'category_id' => $node->getCategoryId()->getValue(),
            ]
        );

        foreach ($node->getChildren() as $child) {
            $this->persistNode($id, $child);
        }
    }
}
