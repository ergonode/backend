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
class UpdateAttributeGroupFormModel
{
    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *       max=32,
     *       maxMessage="Attribute group name is to long, It should have {{ limit }} character or less."
     *     )
     * })
     */
    public array $name;

    /**
     */
    public function __construct()
    {
        $this->name = [];
    }
}
