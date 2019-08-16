<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Factory;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\Entity\ProductStatus;
use Ergonode\Product\Domain\Entity\ProductStatusId;

/**
 */
class ProductStatusFactory
{
    /**
     * @param ProductStatusId    $id
     * @param string             $code
     * @param Color              $color
     * @param TranslatableString $name
     * @param TranslatableString $description
     *
     * @return ProductStatus
     * @throws \Exception
     */
    public function create(ProductStatusId $id, string $code, Color $color, TranslatableString $name, TranslatableString $description): ProductStatus
    {
        return new ProductStatus(
            $id,
            $code,
            $color,
            $name,
            $description
        );
    }
}
