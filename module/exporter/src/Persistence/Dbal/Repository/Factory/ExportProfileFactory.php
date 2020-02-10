<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Persistence\Dbal\Repository\Factory;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class ExportProfileFactory
{
    /**
     * @param array $record
     *
     * @return AbstractExportProfile
     *
     * @throws \ReflectionException
     */
    public function create(array $record): AbstractExportProfile
    {
        $reflector = new \ReflectionClass($record['type']);

        /** @var AbstractExportProfile $object */
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
     */
    private function getMap(array $record): array
    {
        return [
            'id' => new ExportProfileId($record['id']),
            'name' => $record['name'],
            'configuration' => \json_decode($record['configuration'], true, 512, JSON_THROW_ON_ERROR),
        ];
    }
}
