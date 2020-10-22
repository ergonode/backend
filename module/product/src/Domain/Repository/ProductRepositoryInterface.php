<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Repository;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface ProductRepositoryInterface
{
    /**
     * @param ProductId $id
     *
     * @return AbstractProduct
     */
    public function load(ProductId $id): ?AbstractProduct;

    /**
     * @param ProductId $id
     *
     * @return bool
     */
    public function exists(ProductId $id): bool;

    /**
     * @param AbstractProduct $aggregateRoot
     */
    public function save(AbstractProduct $aggregateRoot): void;

    /**
     * @param AbstractProduct $aggregateRoot
     */
    public function delete(AbstractProduct $aggregateRoot): void;
}
