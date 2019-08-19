<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Form\Model;

use Ergonode\Workflow\Infrastructure\Validator\WorkflowExists;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class WorkflowFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=128)
     * @WorkflowExists()
     */
    public $code;

    /**
     * @var StatusFormModel[]
     *
     * @Assert\Valid()
     */
    public $statuses;

    /**
     */
    public function __construct()
    {
        $this->statuses = [];
    }
}
