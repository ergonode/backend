<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Importer\Domain\ValueObject\ImportStatus;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class ImportStatusHandler implements SubscribingHandlerInterface
{
    /**
     * @return array
     */
    public static function getSubscribingMethods(): array
    {
        $methods = [];
        $formats = ['json', 'xml', 'yml'];

        foreach ($formats as $format) {
            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'type' => ImportStatus::class,
                'format' => $format,
                'method' => 'serialize',
            ];

            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => ImportStatus::class,
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    /**
     * @param array $type
     */
    public function serialize(
        SerializationVisitorInterface $visitor,
        ImportStatus $id,
        array $type,
        Context $context
    ): string {
        return $id->getValue();
    }

    /**
     * @param mixed $data
     * @param array $type
     */
    public function deserialize(
        DeserializationVisitorInterface $visitor,
        $data,
        array $type,
        Context $context
    ): ImportStatus {
        return new ImportStatus($data);
    }
}
