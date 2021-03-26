<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Update;

use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Command\Update\UpdateVariableProductCommand;
use Ergonode\Product\Infrastructure\Handler\AbstractUpdateProductHandler;
use Ergonode\Product\Domain\Entity\VariableProduct;

class UpdateVariableProductCommandHandler extends AbstractUpdateProductHandler
{
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
        $product = $this->updateAudit($product);

        $this->productRepository->save($product);
    }
}
