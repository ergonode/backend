<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Model\Product\Relation;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Attribute\Infrastructure\Validator\AttributeExists;

/**
 */
class ProductChildFormModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Child product is required", groups={"Create"})
     * @Assert\Uuid(groups={"Create"})
     *
     * @AttributeExists(groups={"Create"})
     */
    public ?string $childId = null;
}
