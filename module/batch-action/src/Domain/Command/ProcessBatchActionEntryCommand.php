<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Command;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\DomainCommandInterface;

class ProcessBatchActionEntryCommand implements DomainCommandInterface
{
    private BatchActionId $id;

    private AggregateId $resourceId;

    public function __construct(
        BatchActionId $id,
        AggregateId $resourceId
    ) {
        $this->id = $id;
        $this->resourceId = $resourceId;
    }

    public function getId(): BatchActionId
    {
        return $this->id;
    }

    public function getResourceId(): AggregateId
    {
        return $this->resourceId;
    }
}
