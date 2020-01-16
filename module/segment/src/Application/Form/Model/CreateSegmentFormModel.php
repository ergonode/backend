<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Application\Form\Model;

use Ergonode\Segment\Infrastructure\Validator\UniqueSegmentCode;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class CreateSegmentFormModel
{
    /**
     * @var string
     *
     * @Assert\Uuid()
     */
    public $conditionSetId;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Segment code is required")
     * @Assert\Length(max=100)
     *
     * @UniqueSegmentCode()
     */
    public $code;

    /**
     * @var array
     */
    public $name;

    /**
     * @var array
     */
    public $description;

    /**
     */
    public function __construct()
    {
        $this->name = [];
        $this->description = [];
    }
}
