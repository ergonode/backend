<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Application\Form\Model;

use Ergonode\Segment\Application\Validator as SegmentAssert;
use Ergonode\SharedKernel\Application\Validator as SharedKernelAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CreateSegmentFormModel
{
    /**
     * @Assert\Uuid()
     */
    public ?string $conditionSetId;

    /**
     * @Assert\NotBlank(message="System name is required")
     *
     * @SharedKernelAssert\SystemCode(max="100")
     * @SegmentAssert\SegmentCodeUnique()
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
