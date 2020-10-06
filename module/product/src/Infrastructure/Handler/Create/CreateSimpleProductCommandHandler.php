<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler\Create;

use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Command\Create\CreateSimpleProductCommand;
use Ergonode\Product\Infrastructure\Handler\AbstractCreateProductHandler;

/**
 */
class CreateSimpleProductCommandHandler extends AbstractCreateProductHandler
{
    /**
     * @param CreateSimpleProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateSimpleProductCommand $command)
    {
        $attributes = $command->getAttributes();

        $attributes = $this->addAudit($attributes);
        $attributes = $this->addStatusAttribute($attributes);

        $product = new SimpleProduct(
            $command->getId(),
            $command->getSku(),
            $command->getTemplateId(),
            $command->getCategories(),
            $attributes,
        );

        $this->productRepository->save($product);
    }
}
