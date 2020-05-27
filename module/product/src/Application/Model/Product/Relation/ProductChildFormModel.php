<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Model\Product\Relation;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Product\Infrastructure\Validator\ProductExists;

/**
 */
class ProductChildFormModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Child product is required")
     * @Assert\Uuid(strict=true)
     *
     * @ProductExists()
     */
    public ?string $childId = null;
}
