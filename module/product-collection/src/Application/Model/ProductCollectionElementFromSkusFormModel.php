<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Product\Application\Validator as ProductAssert;

class ProductCollectionElementFromSkusFormModel
{
    /**
     * @var string[]
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *
     *     @ProductAssert\Sku(),
     *     @ProductAssert\SkuExists()
     * })
     */
    public array $skus = [];
}
