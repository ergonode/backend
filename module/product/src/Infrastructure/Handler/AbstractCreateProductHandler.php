<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;

abstract class AbstractCreateProductHandler
{
    protected ProductRepositoryInterface $productRepository;

    protected ProductFactoryInterface $productFactory;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductFactoryInterface $productFactory
    ) {
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
    }
}
