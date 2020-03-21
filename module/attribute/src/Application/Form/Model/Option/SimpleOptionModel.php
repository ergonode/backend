<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Model\Option;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

/**
 */
class SimpleOptionModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Option code is required")
     * @Assert\Length(max=128, maxMessage="Option code is to long. It should have {{ limit }} character or less.")
     */
    public ?string $code = null;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *      max=32,
     *      maxMessage="Attribute name is to long, It should have {{ limit }} character or less."
     *     )
     * })
     */
    public array $label = [];
}
