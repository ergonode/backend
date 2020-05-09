<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Entity;

use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
interface ProductInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return Sku
     */
    public function getSku(): Sku;

    /**
     * @return ProductId
     */
    public function getId(): ProductId;
}
