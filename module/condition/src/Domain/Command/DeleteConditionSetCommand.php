<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

class DeleteConditionSetCommand implements ConditionCommandInterface
{
    private ConditionSetId $id;

    public function __construct(ConditionSetId $id)
    {
        $this->id = $id;
    }

    public function getId(): ConditionSetId
    {
        return $this->id;
    }
}
