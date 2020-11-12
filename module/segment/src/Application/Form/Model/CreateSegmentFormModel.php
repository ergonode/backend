<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Form\Model;

use Ergonode\Segment\Infrastructure\Validator\UniqueSegmentCode;
use Symfony\Component\Validator\Constraints as Assert;

class CreateSegmentFormModel
{
    /**
     * @Assert\Uuid()
     */
    public ?string $conditionSetId;

    /**
     * @Assert\NotBlank(message="System name is required")
     * @Assert\Length(
     *     min="3",
     *     max="100",
     *     minMessage="System name is too short. It should have at least {{ limit }} characters.",
     *     maxMessage="System name is too long. It should contain {{ limit }} characters or less."
     * )
     *
     * @UniqueSegmentCode()
     */
    public ?string $code;

    /**
     * @var array
     */
    public array $name;

    /**
     * @var array
     */
    public array $description;

    public function __construct()
    {
        $this->conditionSetId = null;
        $this->code = null;
        $this->name = [];
        $this->description = [];
    }
}
