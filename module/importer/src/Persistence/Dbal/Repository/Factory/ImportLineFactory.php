<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository\Factory;

use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Importer\Domain\Entity\ImportLine;
use Ergonode\Importer\Domain\Entity\ImportLineId;

/**
 * Class ImportLineFactory
 */
class ImportLineFactory
{
    /**
     * @param array $record
     *
     * @return ImportLine
     *
     * @throws \ReflectionException
     */
    public function create(array $record): ImportLine
    {
        $reflector = new \ReflectionClass(ImportLine::class);
        /** @var ImportLine $object */
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
     * @return array
     */
    private function getMap(array $record): array
    {
        return [
            'id' => new ImportLineId($record['id']),
            'importId' => new ImportId($record['id']),
            'content' => $record['line'],
        ];
    }
}
