<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Repository;

use Ergonode\Category\Domain\Entity\CategoryTree;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

interface TreeRepositoryInterface
{
    /**
     * @return null|CategoryTree
     */
    public function load(CategoryTreeId $id): ?AbstractAggregateRoot;

    public function exists(CategoryTreeId $id): bool;

    public function save(AbstractAggregateRoot $aggregateRoot): void;

    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
