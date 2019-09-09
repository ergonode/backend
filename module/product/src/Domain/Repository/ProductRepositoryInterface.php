<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\ProductId;

/**
 */
interface ProductRepositoryInterface
{
    /**
     * @param ProductId $id
     *
     * @return AbstractAggregateRoot|AbstractProduct
     */
    public function load(ProductId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
