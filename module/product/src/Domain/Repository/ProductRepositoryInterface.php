<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Repository;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface ProductRepositoryInterface
{
    public function load(ProductId $id): ?AbstractProduct;

    public function exists(ProductId $id): bool;

    public function save(AbstractProduct $aggregateRoot): void;

    public function delete(AbstractProduct $aggregateRoot): void;
}
