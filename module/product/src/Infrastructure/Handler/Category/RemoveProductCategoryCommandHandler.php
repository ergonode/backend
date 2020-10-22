<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler\Category;

use Ergonode\Product\Domain\Command\Category\RemoveProductCategoryCommand;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;

class RemoveProductCategoryCommandHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param RemoveProductCategoryCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(RemoveProductCategoryCommand $command)
    {
        $product = $this->productRepository->load($command->getId());
        Assert::notNull($product);

        $product->removeFromCategory($command->getCategoryId());

        $this->productRepository->save($product);
    }
}
