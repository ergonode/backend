<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Create;

use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Command\Create\CreateVariableProductCommand;
use Ergonode\Product\Infrastructure\Handler\AbstractCreateProductHandler;

class CreateVariableProductCommandHandler extends AbstractCreateProductHandler
{
    /**
     * @throws \Exception
     */
    public function __invoke(CreateVariableProductCommand $command)
    {
        $attributes = $command->getAttributes();

        $attributes = $this->addAudit($attributes);
        $attributes = $this->addStatusAttribute($attributes);

        $product = new VariableProduct(
            $command->getId(),
            $command->getSku(),
            $command->getTemplateId(),
            $command->getCategories(),
            $attributes,
        );

        $this->productRepository->save($product);
    }
}
