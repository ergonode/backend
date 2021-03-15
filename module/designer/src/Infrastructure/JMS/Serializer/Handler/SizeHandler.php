<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Designer\Domain\ValueObject\Size;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class SizeHandler implements SubscribingHandlerInterface
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
                'type' => Size::class,
                'format' => $format,
                'method' => 'serialize',
            ];

            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => Size::class,
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    /**
     * @param array $type
     *
     * @return array
     */
    public function serialize(SerializationVisitorInterface $visitor, Size $size, array $type, Context $context): array
    {
        return ['width' => $size->getWidth(), 'height' => $size->getHeight()];
    }

    /**
     * @param mixed $data
     * @param array $type
     */
    public function deserialize(DeserializationVisitorInterface $visitor, $data, array $type, Context $context): Size
    {
        return new Size($data['width'], $data['height']);
    }
}
