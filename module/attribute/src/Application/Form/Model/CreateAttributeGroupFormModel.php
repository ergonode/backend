<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Form\Model;

use Ergonode\Attribute\Application\Validator as AttributeAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAttributeGroupFormModel
{
    /**
     * @Assert\NotBlank(message="System name is required")
     * @AttributeAssert\AttributeGroupCode()
     * @AttributeAssert\AttributeGroupCodeUnique()
     */
    public ?string $code;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *         max=32,
     *         maxMessage="Attribute group name is too long. It should contain {{ limit }} characters or less."
     *     )
     * })
     */
    public array $name;

    public function __construct()
    {
        $this->code = null;
        $this->name = [];
    }
}
