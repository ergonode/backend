<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class UpdateSegmentFormModel
{
    /**
     * @var string
     *
     * @Assert\Uuid()
     */
    public $conditionSetId;

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
