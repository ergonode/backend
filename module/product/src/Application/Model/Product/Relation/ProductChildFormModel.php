<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Relation;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Product\Application\Validator as ProductAssert;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 * @ProductAssert\ProductChild
 * @ProductAssert\ProductInvalidChild(groups={"VARIABLE-PRODUCT"})
 */
class ProductChildFormModel
{
    /**
     * @ProductAssert\ProductType(type={"VARIABLE-PRODUCT", "GROUPING-PRODUCT"})
     */
    private ProductId $parentId;

    /**
     * @Assert\NotBlank(message="Child product is required")
     * @Assert\Uuid(strict=true)
     *
     * @ProductAssert\ProductExists()
     * @ProductAssert\ProductType(type={"SIMPLE-PRODUCT"})
     */
    public ?string $childId = null;

    public function __construct(ProductId $parentId)
    {
        $this->parentId = $parentId;
    }

    public function getParentId(): ProductId
    {
        return $this->parentId;
    }
}
