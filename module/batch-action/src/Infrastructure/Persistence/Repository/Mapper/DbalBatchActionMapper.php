<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionAction;

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

    /**
     * @throws \ReflectionException
     */
    public function create(array $record): BatchAction
    {
        $reflector = new \ReflectionClass(BatchAction::class);
        /** @var BatchAction $object */
        $object = $reflector->newInstanceWithoutConstructor();

        $map = [
            'id' => new BatchActionId($record['id']),
            'type' => new BatchActionType($record['resource_type']),
            'action' => new BatchActionAction($record['action']),
        ];

        foreach ($map as $key => $value) {
            $property = $reflector->getProperty($key);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        }

        return $object;
    }
}
