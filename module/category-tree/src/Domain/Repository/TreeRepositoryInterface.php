<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\CategoryTree\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\CategoryTree\Domain\Entity\CategoryTree;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;

/**
 */
interface TreeRepositoryInterface
{
    /**
     * @param CategoryTreeId $id
     *
     * @return null|CategoryTree
     */
    public function load(CategoryTreeId $id): ?AbstractAggregateRoot;

    /**
     * @param CategoryTreeId $id
     *
     * @return bool
     */
    public function exists(CategoryTreeId $id): bool;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;
}
