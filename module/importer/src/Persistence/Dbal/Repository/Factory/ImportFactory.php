<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository\Factory;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Importer\Domain\Entity\Source\SourceId;
use Ergonode\Importer\Domain\ValueObject\ImportStatus;
use Ergonode\Transformer\Domain\Entity\TransformerId;

/**
 */
class ImportFactory
{
    /**
     * @param array $record
     *
     * @return Import
     *
     * @throws \ReflectionException
     */
    public function create(array $record): Import
    {
        $reflector = new \ReflectionClass(Import::class);
        /** @var Import $object */
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
            'status' => new ImportStatus($record['status']),
            'sourceId' => new SourceId($record['source_id']),
            'transformerId' => new TransformerId($record['transformer_id']),
        ];
    }
}
