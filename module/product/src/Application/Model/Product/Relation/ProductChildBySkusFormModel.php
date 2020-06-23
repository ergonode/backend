<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Model\Product\Relation;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Product\Infrastructure\Validator\Sku;
use Ergonode\Product\Infrastructure\Validator\ProductSkuExists;

/**
 */
class ProductChildBySkusFormModel
{
    /**
     * @var string[]|null
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *
     *     @Sku(),
     *
     *     @ProductSkuExists()
     * })
     */
    public array $skus = [];
}
