<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\SharedKernel\Domain\Aggregate;

use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
class WorkflowId extends AggregateId
{
    public const NAMESPACE = '34f4084f-7cc8-4db3-b4b4-5f75263a44a3';

    /**
     * @param string $value
     *
     * @return WorkflowId
     */
    public static function fromCode(string $value): WorkflowId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $value)->getValue());
    }
}
