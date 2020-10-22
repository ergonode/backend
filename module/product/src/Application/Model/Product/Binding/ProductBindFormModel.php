<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Model\Product\Binding;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Attribute\Infrastructure\Validator\AttributeExists;

class ProductBindFormModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Bind attribute is required")
     * @Assert\Uuid(strict=true)
     *
     * @AttributeExists()
     */
    public ?string $bindId = null;
}
