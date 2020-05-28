<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository\Factory;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\Exporter\Domain\Entity\ExportLine;
use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
class ExportLineFactory
{
    /**
     * @param array $record
     *
     * @return ExportLine
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function create(array $record): ExportLine
    {
        $reflector = new \ReflectionClass(ExportLine::class);
        /** @var ExportLine $object */
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
            'exportId' => new ExportId($record['export_id']),
            'objectId' => new AggregateId($record['object_id']),
            'processedAt' => $record['processed_at'] ? new \DateTime($record['processed_at']) : null,
            'error' => $record['message'],
        ];
    }
}
