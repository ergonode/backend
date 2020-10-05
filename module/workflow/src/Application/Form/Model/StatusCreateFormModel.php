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

/**
 */
class StatusCreateFormModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=100, maxMessage="Status code is to long, It should have {{ limit }} character or less.")
     * @Assert\Regex(pattern="/^[a-zA-Z0-9-_ ]+$\b/i")
     *
     * @ErgoAssert\StatusCodeUnique()
     */
    public ?string $code;

    /**
     * @var Color|null
     *
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
     *     @Assert\Length(max=100, maxMessage="Status name is to long, It should have {{ limit }} character or less.")
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
        $this->code = null;
        $this->color = null;
        $this->name = [];
        $this->description = [];
    }
}
