<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Metadata\VirtualPropertyMetadata;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;

/**
 */
class ValueInterfaceHandler implements SubscribingHandlerInterface
{
    /**
     * @var array
     */
    private $map;

    /**
     * @param string $class
     *
     * @throws \ReflectionException
     */
    public function set(string $class): void
    {
        $type = (new \ReflectionClass($class))->getConstant('TYPE');
        $this->map[$type] = $class;
    }

    /**
     * @return array
     */
    public static function getSubscribingMethods(): array
    {
        $methods = [];
        $formats = ['json', 'xml', 'yml'];

        foreach ($formats as $format) {
            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => ValueInterface::class,
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    /**
     * @param DeserializationVisitorInterface $visitor
     * @param array                           $data
     * @param array                           $type
     * @param Context                         $context
     *
     * @return ValueInterface
     * @throws \ReflectionException
     */
    public function deserialize(DeserializationVisitorInterface $visitor, array $data, array $type, Context $context): ValueInterface
    {
        if (!array_key_exists($data['type'], $this->map)) {
            throw new \OutOfBoundsException(sprintf('Value type "%s" not mapped', $data['type']));
        }

        $class = $this->map[$data['type']];

        $metadata = $context->getMetadataFactory()->getMetadataForClass($class);
        if (null === $metadata) {
            throw new \RuntimeException(sprintf('Cannot read metadata from "%s" class', $class));
        }

        $reflection = new \ReflectionClass($class);
        /** @var ValueInterface $object */
        $object = $reflection->newInstanceWithoutConstructor();

        foreach ($metadata->propertyMetadata as $name => $property) {
            if (!$property instanceof VirtualPropertyMetadata) {
                $value = $data[$name];

                $reflectionProperty = $reflection->getProperty($name);
                if ($reflectionProperty->isPrivate()) {
                    $reflectionProperty->setAccessible(true);
                    $reflectionProperty->setValue($object, $value);
                    $reflectionProperty->setAccessible(false);
                } else {
                    $reflectionProperty->setValue($object, $value);
                }
            }
        }

        return $object;
    }
}
