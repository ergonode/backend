<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Provider;

use Ergonode\BatchAction\Application\Form\Model\BatchActionPayloadModel;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionMessage;

interface BatchActionProcessorInterface
{
    public function supports(BatchActionType $type): bool;

    /**
     * @return BatchActionMessage[]
     */
    public function process(
        BatchActionId $id,
        AggregateId $resourceId,
        ?BatchActionPayloadModel $payload = null
    ): array;
}
