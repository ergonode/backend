<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Update;

use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Command\Update\UpdateVariableProductCommand;
use Ergonode\Product\Domain\Entity\VariableProduct;

class UpdateVariableProductCommandHandler
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateVariableProductCommand $command): void
    {
        /** @var VariableProduct $product */
        $product = $this->productRepository->load($command->getId());
        Assert::isInstanceOf($product, VariableProduct::class);

        $product->changeTemplate($command->getTemplateId());
        $product->changeCategories($command->getCategories());

        $this->productRepository->save($product);
    }
}
