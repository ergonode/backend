<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;

interface ProductCollectionRepositoryInterface
{
    /**
     * @param ProductCollectionId $id
     *
     * @return bool
     */
    public function exists(ProductCollectionId $id): bool;

    /**
     * @param ProductCollectionId $id
     *
     * @return AbstractAggregateRoot|null
     */
    public function load(ProductCollectionId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
