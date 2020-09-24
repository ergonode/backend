<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler;

use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Command\UpdateProductCategoriesCommand;

/**
 */
class UpdateProductCategoriesCommandHandler extends AbstractUpdateProductHandler
{
    /**
     * @param UpdateProductCategoriesCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateProductCategoriesCommand $command)
    {
        $product = $this->productRepository->load($command->getId());
        Assert::notNull($product);

        $product->changeCategories($command->getCategories());
        $product = $this->updateAudit($product);

        $this->productRepository->save($product);
    }
}
