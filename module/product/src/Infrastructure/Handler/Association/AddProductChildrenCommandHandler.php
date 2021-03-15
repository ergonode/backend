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
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\Product\Domain\Command\Relations\AddProductChildrenCommand;

class AddProductChildrenCommandHandler
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(AddProductChildrenCommand $command): void
    {
        /** @var AbstractAssociatedProduct $product */
        $product = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $product,
            AbstractAssociatedProduct::class,
            sprintf('Can\'t find associated product with id "%s"', $command->getId())
        );

        foreach ($command->getChildren() as $childId) {
            $child = $this->repository->load($childId);
            Assert::isInstanceOf(
                $child,
                AbstractProduct::class,
                sprintf('Can\'t find product with id "%s"', $command->getId())
            );

            if (!$child->getId()->isEqual($product->getId())) {
                $product->addChild($child);
            }
        }

        $this->repository->save($product);
    }
}
