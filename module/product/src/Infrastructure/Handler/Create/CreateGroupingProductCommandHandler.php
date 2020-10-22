<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler\Create;

use Ergonode\Product\Domain\Entity\GroupingProduct;
use Ergonode\Product\Domain\Command\Create\CreateGroupingProductCommand;
use Ergonode\Product\Infrastructure\Handler\AbstractCreateProductHandler;

class CreateGroupingProductCommandHandler extends AbstractCreateProductHandler
{
    /**
     * @param CreateGroupingProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateGroupingProductCommand $command)
    {
        $attributes = $command->getAttributes();

        $attributes = $this->addAudit($attributes);
        $attributes = $this->addStatusAttribute($attributes);

        $product = new GroupingProduct(
            $command->getId(),
            $command->getSku(),
            $command->getTemplateId(),
            $command->getCategories(),
            $attributes,
        );

        $this->productRepository->save($product);
    }
}
