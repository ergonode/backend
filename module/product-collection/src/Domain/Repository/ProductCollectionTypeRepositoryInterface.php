<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;

/**
 */
interface ProductCollectionTypeRepositoryInterface
{
    /**
     * @param ProductCollectionTypeId $id
     *
     * @return bool
     */
    public function exists(ProductCollectionTypeId $id): bool;
    /**
     * @param ProductCollectionTypeId $id
     *
     * @return AbstractAggregateRoot|null
     */
    public function load(ProductCollectionTypeId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
