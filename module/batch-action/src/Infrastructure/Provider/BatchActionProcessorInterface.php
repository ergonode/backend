<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Provider;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionMessage;

interface BatchActionProcessorInterface
{
    public function supports(BatchActionType $type): bool;

    /**
     * @param mixed $payload
     *
     * @return BatchActionMessage[]
     */
    public function process(
        BatchActionId $id,
        AggregateId $resourceId,
        $payload = null
    ): array;
}
