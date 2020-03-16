<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class AttributeOptionModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Option code is required")
     * @Assert\Length(max=128, maxMessage="Option code is to long. It should have {{ limit }} character or less.")
     */
    public ?string $key = null;

    /**
     * @var array|string|null
     */
    public $value = null;
}
