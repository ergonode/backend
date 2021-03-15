<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Repository\Factory;

use Ergonode\Channel\Domain\Entity\Scheduler;
use Ergonode\SharedKernel\Domain\AggregateId;

class DbalSchedulerFactory
{
    /**
     * @param array $record
     *
     *
     * @throws \ReflectionException
     */
    public function create(array $record): Scheduler
    {
        $reflector = new \ReflectionClass(Scheduler::class);
        /** @var Scheduler $object */
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
            'id' => new AggregateId($record['id']),
            'active' => $record['active'],
            'hour' => $record['hour'],
            'minute' => $record['minute'],
            'start' => $record['start']?new \DateTime($record['start']): null,
        ];
    }
}
