<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ramsey\Uuid\Uuid;

/**
 */
class WorkflowId extends AbstractId
{
    public const NAMESPACE = '34f4084f-7cc8-4db3-b4b4-5f75263a44a3';

    /**
     * @param string $value
     *
     * @return WorkflowId
     *
     * @throws \Exception
     */
    public static function fromCode(string $value): WorkflowId
    {
        return new static(Uuid::uuid5(self::NAMESPACE, $value)->toString());
    }
}
