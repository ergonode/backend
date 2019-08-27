<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Faker\Provider\Base as BaseProvider;

/**
 */
class WorkflowIdFaker extends BaseProvider
{
    /**
     * @param string|null $code
     *
     * @return WorkflowId
     *
     * @throws \Exception
     */
    public function workflowId(?string $code = null): WorkflowId
    {
        if ($code) {
            return WorkflowId::fromCode($code);
        }

        return WorkflowId::generate();
    }
}
