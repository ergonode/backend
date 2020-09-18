<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler\Update;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Command\Update\UpdateVariableProductCommand;
use Ergonode\Product\Infrastructure\Handler\AbstractUpdateProductHandler;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;

/**
 */
class UpdateVariableProductCommandHandler extends AbstractUpdateProductHandler
{
    /**
     * @param UpdateVariableProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateVariableProductCommand $command)
    {
        /** @var VariableProduct $product */
        $product = $this->productRepository->load($command->getId());
        Assert::isInstanceOf($product, VariableProduct::class);

        $product->changeCategories($command->getCategories());
        $product = $this->updateAudit($product);

        $this->productRepository->save($product);
    }
}
