<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class AbstractCreateProductHandler
{
    protected ProductRepositoryInterface $productRepository;

    protected ProductFactoryInterface $productFactory;

    protected MessageBusInterface $messageBus;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductFactoryInterface $productFactory,
        MessageBusInterface $messageBus
    ) {
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
        $this->messageBus = $messageBus;
    }
}
