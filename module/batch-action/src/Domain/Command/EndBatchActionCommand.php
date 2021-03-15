<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Command;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\SharedKernel\Domain\DomainCommandInterface;

class EndBatchActionCommand implements DomainCommandInterface
{
    private BatchActionId $id;

    public function __construct(BatchActionId $id)
    {
        $this->id = $id;
    }

    public function getId(): BatchActionId
    {
        return $this->id;
    }
}
