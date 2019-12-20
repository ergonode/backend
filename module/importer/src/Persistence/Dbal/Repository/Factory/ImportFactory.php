<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository\Factory;

use Ergonode\Importer\Domain\Entity\AbstractImport;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Importer\Domain\ValueObject\ImportStatus;

/**
 */
class ImportFactory
{
    /**
     * @param array $record
     *
     * @return AbstractImport
     *
     * @throws \ReflectionException
     */
    public function create(array $record): AbstractImport
    {
        $reflector = new \ReflectionClass($record['type']);
        /** @var AbstractImport $object */
        $object =  $reflector->newInstanceWithoutConstructor();

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
     */
    private function getMap(array $record): array
    {
        return [
            'id' => new ImportId($record['id']),
            'name' => $record['name'],
            'status' => new ImportStatus($record['status']),
            'options' => \json_decode($record['options'], true),
            'reason' => $record['reason'],
        ];
    }
}
