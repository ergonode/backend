<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler\Association;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\Product\Domain\Command\Relations\RemoveProductChildCommand;

/**
 */
class RemoveProductChildCommandHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $repository;

    /**
     * @param ProductRepositoryInterface $repository
     */
    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param RemoveProductChildCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(RemoveProductChildCommand $command): void
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

        $product->removeChild($child->getId());

        $this->repository->save($product);
    }
}
