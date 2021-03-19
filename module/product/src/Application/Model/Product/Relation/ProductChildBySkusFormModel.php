<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Relation;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Product\Application\Validator as ProductAssert;

/**
 * @ProductAssert\ProductInvalidChildren(groups={"VARIABLE-PRODUCT"})
 */
class ProductChildBySkusFormModel
{
    public ?AbstractProduct $parentProduct;

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
    public array $skus;

    public function __construct(?AbstractProduct $parentProduct)
    {
        $this->parentProduct = $parentProduct;
        $this->skus = [];
    }
}
