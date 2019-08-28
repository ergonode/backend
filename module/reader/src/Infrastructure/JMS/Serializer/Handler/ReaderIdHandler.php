<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Reader\Domain\Entity\ReaderId;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 */
class ReaderIdHandler implements SubscribingHandlerInterface
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
                'type' => ReaderId::class,
                'format' => $format,
                'method' => 'serialize',
            ];

            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => ReaderId::class,
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    /**
     * @param SerializationVisitorInterface $visitor
     * @param ReaderId                      $id
     * @param array                         $type
     * @param Context                       $context
     *
     * @return string
     */
    public function serialize(SerializationVisitorInterface $visitor, ReaderId $id, array $type, Context $context): string
    {
        return $id->getValue();
    }

    /**
     * @param DeserializationVisitorInterface $visitor
     * @param string                          $data
     * @param array                           $type
     * @param Context                         $context
     *
     * @return ReaderId
     */
    public function deserialize(DeserializationVisitorInterface $visitor, $data, array $type, Context $context): ReaderId
    {
        return new ReaderId($data);
    }
}
