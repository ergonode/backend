<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;

interface ProductCollectionRepositoryInterface
{
    public function exists(ProductCollectionId $id): bool;

    public function load(ProductCollectionId $id): ?AbstractAggregateRoot;

    public function save(AbstractAggregateRoot $aggregateRoot): void;

    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
