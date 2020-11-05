<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Command;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;

class ProcessBatchActionEntryCommand implements DomainCommandInterface
{
    private BatchActionId $id;

    private BatchActionType $type;

    private AggregateId $resourceId;

    public function __construct(BatchActionId $id, BatchActionType $type, AggregateId $resourceId)
    {
        $this->id = $id;
        $this->type = $type;
        $this->resourceId = $resourceId;
    }

    public function getId(): BatchActionId
    {
        return $this->id;
    }

    public function getType(): BatchActionType
    {
        return $this->type;
    }

    public function getResourceId(): AggregateId
    {
        return $this->resourceId;
    }
}
