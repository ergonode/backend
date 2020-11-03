<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Persistence\Repository\Factory;

use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionAction;

class DbalBatchActionFactory
{
    /**
     * @throws \ReflectionException
     */
    public function create(array $record): BatchAction
    {
        $reflector = new \ReflectionClass(BatchAction::class);
        /** @var BatchAction $object */
        $object = $reflector->newInstanceWithoutConstructor();

        foreach ($this->getMap($record) as $key => $value) {
            $property = $reflector->getProperty($key);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        }

        return $object;
    }

    /**
     * @param array $record
     *
     * @return array
     *
     * @throws \Exception
     */
    private function getMap(array $record): array
    {
        return [
            'id' => new BatchActionId($record['id']),
            'type' => new BatchActionType($record['resource_type']),
            'action' => new BatchActionAction($record['action']),
        ];
    }
}
