<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Form\Model;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Workflow\Infrastructure\Validator as ErgoAssert;
use Symfony\Component\Validator\Constraints as Assert;

class StatusCreateFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max=100,
     *     maxMessage="System name is too long. It should contain {{ limit }} characters or less."
     * )
     * @Assert\Regex(pattern="/^[a-zA-Z0-9-_ ]+$\b/i")
     *
     * @ErgoAssert\StatusCodeUnique()
     */
    public ?string $code;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=4,
     *     max="7",
     *     minMessage="Color must be in hex format", maxMessage="Color must be in hex format"
     * )
     */
    public ?Color $color;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *          max=100,
     *          maxMessage="Status name is too long. It should contain {{ limit }} characters or less."
     *     )
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
     *      maxMessage="Status description is too long. It should contain {{ limit }} characters or less."
     *  )
     * })
     */
    public array $description;

    public function __construct()
    {
        $this->code = null;
        $this->color = null;
        $this->name = [];
        $this->description = [];
    }
}
