<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;

class DbalBatchActionMapper
{
    public function map(BatchAction $batchAction): array
    {
        return [
            'id' => $batchAction->getId(),
            'type' => $batchAction->getType()->getValue(),
            'payload' => serialize($batchAction->getPayload()),
        ];
    }

    public function create(array $record): BatchAction
    {
        $id = new BatchActionId($record['id']);
        $type = new BatchActionType($record['type']);
        $payload = null;
        if (null !== $record['payload'] && 'null' !== $record['payload']) {
            $payload = unserialize($record['payload']);
        }

        return new BatchAction($id, $type, $payload);
    }
}
