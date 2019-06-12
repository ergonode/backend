<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Attribute\Domain\ValueObject\AttributeValueType;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 */
class AttributeValueTypeHandler implements SubscribingHandlerInterface
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
                'type' => AttributeValueType::class,
                'format' => $format,
                'method' => 'serialize',
            ];

            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => AttributeValueType::class,
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    /**
     * @param SerializationVisitorInterface $visitor
     * @param AttributeValueType            $attributeValueType
     * @param array                         $type
     * @param Context                       $context
     *
     * @return string
     */
    public function serialize(SerializationVisitorInterface $visitor, AttributeValueType $attributeValueType, array $type, Context $context): string
    {
        return $attributeValueType->getValue();
    }

    /**
     * @param DeserializationVisitorInterface $visitor
     * @param mixed                           $data
     * @param array                           $type
     * @param Context                         $context
     *
     * @return AttributeValueType
     */
    public function deserialize(DeserializationVisitorInterface $visitor, $data, array $type, Context $context): AttributeValueType
    {
        return new AttributeValueType($data);
    }
}
