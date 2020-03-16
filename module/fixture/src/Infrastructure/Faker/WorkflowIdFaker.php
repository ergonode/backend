<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class WorkflowIdFaker extends BaseProvider
{
    private const NAMESPACE = '34f4084f-7cc8-4db3-b4b4-5f75263a44a3';

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
            return new WorkflowId(Uuid::uuid5(self::NAMESPACE, $code)->toString());
        }

        return WorkflowId::generate();
    }
}
