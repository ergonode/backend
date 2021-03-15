<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Strategy;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

interface ProductFactoryStrategyInterface
{
    public function supports(string $type): bool;

    public function build(
        ProductId $id,
        Sku $sku,
        TemplateId $templateId,
        array $categories,
        array $attributes
    ): AbstractProduct;
}
