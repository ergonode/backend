<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Association;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Command\Relations\AddProductChildCommand;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;

class AddProductChildCommandHandler
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(AddProductChildCommand $command): void
    {
        /** @var AbstractAssociatedProduct $product */
        $product = $this->repository->load($command->getId());
        $child = $this->repository->load($command->getChildId());
        Assert::isInstanceOf(
            $product,
            AbstractAssociatedProduct::class,
            sprintf('Can\'t find associated product with id "%s"', $command->getId())
        );

        Assert::isInstanceOf(
            $child,
            AbstractProduct::class,
            sprintf('Can\'t find product with id "%s"', $command->getId())
        );

        $product->addChild($child);

        $this->repository->save($product);
    }
}
