<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Form\Model;

use Ergonode\Workflow\Application\Validator as WorkflowAssert;
use Symfony\Component\Validator\Constraints as Assert;

class StatusOrderSetFormModel
{
    /**
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Uuid (),
     *     @WorkflowAssert\StatusExists()
     * })
     * @WorkflowAssert\StatusIdsContainAll()
     */
    public array $statusIds;

    public function __construct()
    {
        $this->statusIds = [];
    }
}
