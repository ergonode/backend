<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Repository\Factory;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\ValueObject\ImportStatus;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

class DbalImportFactory
{
    /**
     * @param array $record
     *
     * @throws \ReflectionException
     */
    public function create(array $record): Import
    {
        $reflector = new \ReflectionClass(Import::class);
        /** @var Import $object */
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
            'id' => new ImportId($record['id']),
            'status' => new ImportStatus($record['status']),
            'sourceId' => new SourceId($record['source_id']),
            'file' => $record['file'],
            'startedAt' => $record['started_at'] ? new \DateTime($record['started_at']) : null,
            'endedAt' => $record['ended_at'] ? new \DateTime($record['ended_at']) : null,
        ];
    }
}
