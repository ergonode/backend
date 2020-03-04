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
class StatusChangeFormModel
{
    /**
     * @var Color|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=4,
     *     max="7",
     *     minMessage="Color must be in hex format", maxMessage="Color must be in hex format"
     *  )
     */
    public ?Color $color;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *      max=100,
     *      maxMessage="Status name is to long, It should have {{ limit }} character or less."
     *  )
     * })
     */
    public array $name;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *      max=500,
     *      maxMessage="Status description is to long,. It should have {{ limit }} character or less."
     *  )
     * })
     */
    public array $description;

    /**
     */
    public function __construct()
    {
        $this->color = null;
        $this->name = [];
        $this->description = [];
    }
}
