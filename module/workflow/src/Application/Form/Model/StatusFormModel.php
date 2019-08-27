<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Form\Model;

use Ergonode\Core\Domain\ValueObject\Color;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class StatusFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=100, maxMessage="Status code is to long, It should have {{ limit }} character or less.")
     */
    public $code;

    /**
     * @var Color
     */
    public $color;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=100, maxMessage="Status name is to long, It should have {{ limit }} character or less.")
     * })
     */
    public $name;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=500, maxMessage="Status description is to long,. It should have {{ limit }} character or less.")
     * })
     */
    public $description;
}
