<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Infrastructure\JMS\Serializer\Handler;

use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class ImportLineIdHandler implements SubscribingHandlerInterface
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
                'type' => ImportLineId::class,
                'format' => $format,
                'method' => 'serialize',
            ];

            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => ImportLineId::class,
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    public function serialize(
        SerializationVisitorInterface $visitor,
        ImportLineId $id,
        array $type,
        Context $context
    ): string {
        return $id->getValue();
    }

    public function deserialize(
        DeserializationVisitorInterface $visitor,
        string $data,
        array $type,
        Context $context
    ): ImportLineId {
        return new ImportLineId($data);
    }
}
