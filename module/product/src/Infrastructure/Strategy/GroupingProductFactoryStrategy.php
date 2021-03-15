<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Strategy;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\GroupingProduct;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class GroupingProductFactoryStrategy implements ProductFactoryStrategyInterface
{

    public function supports(string $type): bool
    {
        return GroupingProduct::TYPE === $type;
    }

    /**
     * @throws \Exception
     */
    public function build(
        ProductId $id,
        Sku $sku,
        TemplateId $templateId,
        array $categories,
        array $attributes
    ): AbstractProduct {
        return new GroupingProduct($id, $sku, $templateId, $categories, $attributes);
    }
}
