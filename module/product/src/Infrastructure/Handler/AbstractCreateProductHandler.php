<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

abstract class AbstractCreateProductHandler
{
    protected ProductRepositoryInterface $productRepository;

    protected ProductFactoryInterface $productFactory;

    protected ApplicationEventBusInterface $eventBus;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductFactoryInterface $productFactory,
        ApplicationEventBusInterface $eventBus
    ) {
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
        $this->eventBus = $eventBus;
    }
}
