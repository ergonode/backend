<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Persistence\Dbal\Repository\Factory;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;

/**
 */
class MultimediaFactory
{
    /**
     * @param array $record
     *
     * @return Multimedia
     *
     * @throws \ReflectionException
     */
    public function create(array $record): Multimedia
    {
        $reflector = new \ReflectionClass(Multimedia::class);
        /** @var Multimedia $object */
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
            'id' => new MultimediaId($record['id']),
            'name' => $record['name'],
            'extension' => $record['extension'],
            'size' => $record['extension'],
            'crc' => $record['extension'],
            'mime' => $record['mime'],
        ];
    }
}
