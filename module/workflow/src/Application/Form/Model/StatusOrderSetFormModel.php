<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Form\Model;

use Ergonode\Workflow\Application\Validator as WorkflowAssert;

class StatusOrderSetFormModel
{
    /**
     * @WorkflowAssert\StatusIdsContainAll()
     */
    public array $statusIds;

    public function __construct()
    {
        $this->statusIds = [];
    }
}
