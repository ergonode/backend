<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product;

use Ergonode\Product\Application\Validator as ProductAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ProductTypeFormModel
{
    /**
     * @Assert\NotBlank(message="Type is required")
     *
     * @ProductAssert\ProductTypeExists()
     */
    public ?string $type = null;
}
