<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Model;

use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Attribute\Infrastructure\Validator as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class CreateAttributeGroupFormModel
{
    /**
     * @var AttributeGroupCode
     *
     * @Assert\NotBlank(message="Attribute code is required")
     * @Assert\Length(max=128)
     *
     * @AppAssert\AttributeGroupCode()
     */
    public $code;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=32, maxMessage="Attribute name is to long, It should have {{ limit }} character or less.")
     * })
     */
    public $name;

    /**
     */
    public function __construct()
    {
        $this->name = [];
    }
}
