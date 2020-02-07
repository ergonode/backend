<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository\Factory;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use http\Exception\RuntimeException;

/**
 */
class SourceFactory
{
    /**
     * @param array $record
     *
     * @return AbstractSource
     *
     * @throws \ReflectionException
     */
    public function create(array $record): AbstractSource
    {
        $reflector = new \ReflectionClass($record['type']);
        /** @var AbstractSource $object */
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
            'id' => new SourceId($record['id']),
            'configuration' => \json_decode($record['configuration'], true, 512, JSON_THROW_ON_ERROR),
        ];
    }
}
