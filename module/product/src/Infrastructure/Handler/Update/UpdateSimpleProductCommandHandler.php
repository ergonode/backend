<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Update;

use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Command\Update\UpdateSimpleProductCommand;
use Ergonode\Product\Infrastructure\Handler\AbstractUpdateProductHandler;

class UpdateSimpleProductCommandHandler extends AbstractUpdateProductHandler
{
    /**
     * @throws \Exception
     */
    public function __invoke(UpdateSimpleProductCommand $command): void
    {
        $product = $this->productRepository->load($command->getId());
        Assert::notNull($product);

        $product->changeTemplate($command->getTemplateId());
        $product->changeCategories($command->getCategories());
        $product = $this->updateAudit($product);

        $this->productRepository->save($product);
    }
}
