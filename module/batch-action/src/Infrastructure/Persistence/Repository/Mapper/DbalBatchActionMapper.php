<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\BatchAction\Domain\Entity\BatchAction;

class DbalBatchActionMapper
{
    /**
     * @return array
     */
    public function map(BatchAction $batchAction): array
    {
        return [
            'id' => $batchAction->getId(),
            'resource_type' => $batchAction->getType()->getValue(),
            'action' => $batchAction->getAction()->getValue(),
        ];
    }
}
