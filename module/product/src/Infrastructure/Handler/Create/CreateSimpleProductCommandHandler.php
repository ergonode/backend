<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Create;

use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Command\Create\CreateSimpleProductCommand;
use Ergonode\Product\Application\Event\ProductCreatedEvent;
use Ergonode\Product\Infrastructure\Handler\AbstractCreateProductHandler;

class CreateSimpleProductCommandHandler extends AbstractCreateProductHandler
{
    /**
     * @throws \Exception
     */
    public function __invoke(CreateSimpleProductCommand $command): void
    {
        $product = $this->productFactory->create(
            SimpleProduct::TYPE,
            $command->getId(),
            $command->getSku(),
            $command->getTemplateId(),
            $command->getCategories(),
            $command->getAttributes(),
        );

        $this->productRepository->save($product);
        $this->eventBus->dispatch(new ProductCreatedEvent($product));
    }
}
