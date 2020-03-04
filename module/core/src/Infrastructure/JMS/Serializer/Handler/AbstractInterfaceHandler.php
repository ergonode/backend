<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\JMS\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Metadata\VirtualPropertyMetadata;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;

/**
 */
abstract class AbstractInterfaceHandler implements SubscribingHandlerInterface
{
    /**
     * @var string
     */
    protected string $constant;

    /**
     * @var array
     */
    private array $map;

    /**
     * @param string $constant
     */
    public function __construct(string $constant = 'TYPE')
    {
        $this->constant = $constant;
    }

    /**
     * @param string $class
     *
     * @throws \ReflectionException
     */
    public function set(string $class): void
    {
        $type = (new \ReflectionClass($class))->getConstant($this->constant);
        $this->map[$type] = $class;
    }

    /**
     * @return array
     */
    public static function getSubscribingMethods(): array
    {
        $methods = [];
        $formats = ['json'];

        foreach ($formats as $format) {
            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => static::getSupportedInterface(),
                'format' => $format,
                'method' => 'deserialize',
            ];
        }

        return $methods;
    }

    /**
     * @return string
     */
    abstract public static function getSupportedInterface(): string;

    /**
     * @param DeserializationVisitorInterface $visitor
     * @param array                           $data
     * @param array                           $type
     * @param Context                         $context
     *
     * @return object
     *
     * @throws \ReflectionException
     */
    public function deserialize(DeserializationVisitorInterface $visitor, array $data, array $type, Context $context)
    {
        $typeField = strtolower($this->constant);

        $data = $this->prepareData($data);

        if (!array_key_exists($data[$typeField], $this->map)) {
            throw new \OutOfBoundsException(sprintf('Value type "%s" not mapped', $data[$typeField]));
        }

        $class = $this->map[$data[$typeField]];

        $metadata = $context->getMetadataFactory()->getMetadataForClass($class);
        if (null === $metadata) {
            throw new \RuntimeException(sprintf('Cannot read metadata from "%s" class', $class));
        }

        $reflection = new \ReflectionClass($class);
        $object = $reflection->newInstanceWithoutConstructor();

        $visitor->startVisitingObject($metadata, $object, ['name' => $class]);
        foreach ($metadata->propertyMetadata as $name => $property) {
            if (!$property instanceof VirtualPropertyMetadata) {
                $value = $visitor->visitProperty($property, $data);

                $reflectionProperty = $reflection->getProperty($property->name);
                if ($reflectionProperty->isPrivate()) {
                    $reflectionProperty->setAccessible(true);
                    $reflectionProperty->setValue($object, $value);
                    $reflectionProperty->setAccessible(false);
                } else {
                    $reflectionProperty->setValue($object, $value);
                }
            }
        }
        $visitor->endVisitingObject($metadata, $object, ['name' => $class]);

        if (!in_array(static::getSupportedInterface(), class_implements($object), false)) {
            throw new \RuntimeException(sprintf(
                'Expected class "%s" but "%s" created',
                static::CLASS,
                get_class($object)
            ));
        }

        return $object;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function prepareData(array $data): array
    {
        return $data;
    }
}
