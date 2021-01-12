<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Attribute\Infrastructure\Validator\AttributeTypeExists;

class AttributeTypeFormModel
{
    /**
     * @Assert\NotBlank(
     *     message="Type of attribute is required",
     *     )
     * @AttributeTypeExists()
     */
    public ?string $type = null;
}
