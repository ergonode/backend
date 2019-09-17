<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Condition\Domain\ValueObject\ConditionSetCode;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 */
class ConditionSetCodeHandler implements SubscribingHandlerInterface
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
                'type' => ConditionSetCode::class,
                'format' => $format,
                'method' => 'serialize',
            ];

            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => ConditionSetCode::class,
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    /**
     * @param SerializationVisitorInterface $visitor
     * @param ConditionSetCode              $status
     * @param array                         $type
     * @param Context                       $context
     *
     * @return string
     */
    public function serialize(SerializationVisitorInterface $visitor, ConditionSetCode $status, array $type, Context $context): string
    {
        return (string) $status;
    }

    /**
     * @param DeserializationVisitorInterface $visitor
     * @param mixed                           $data
     * @param array                           $type
     * @param Context                         $context
     *
     * @return ConditionSetCode
     */
    public function deserialize(DeserializationVisitorInterface $visitor, $data, array $type, Context $context): ConditionSetCode
    {
        return new ConditionSetCode($data);
    }
}
