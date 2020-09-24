<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Persistence\Repository\Factory;

use Ergonode\Importer\Domain\Entity\ImportError;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

/**
 */
class DbalImportErrorFactory
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
