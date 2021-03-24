<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Binding;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Attribute\Application\Validator as AttributeAssert;
use Ergonode\Product\Application\Validator as ProductAssert;

/**
 * @ProductAssert\ProductHasChildren()
 * */
class ProductBindFormModel
{
    /**
     * @Assert\NotBlank(message="Bind attribute is required")
     * @Assert\Uuid(strict=true)
     *
     * @AttributeAssert\AttributeExists()
     */
    public ?string $bindId = null;

    public AbstractProduct $product;

    public function __construct(AbstractProduct $product)
    {
        $this->product = $product;
    }
}
