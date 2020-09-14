<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository\Factory;

use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Importer\Domain\Entity\ImportError;

/**
 */
class ImportErrorFactory
{
    /**
     * @param array $record
     *
     * @return ImportError
     *
     * @throws \ReflectionException
     */
    public function create(array $record): ImportError
    {
        $reflector = new \ReflectionClass(ImportError::class);
        /** @var ImportError $object */
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
            'importId' => new ImportId($record['import_id']),
            'line' => $record['line'],
            'step' => $record['step'],
            'message' => $record['message'],
            'createdAt' =>  new \DateTime($record['created_at']),
        ];
    }
}
