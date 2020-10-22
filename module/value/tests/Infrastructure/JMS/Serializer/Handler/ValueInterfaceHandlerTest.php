<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Value\Infrastructure\JMS\Serializer\Handler\ValueInterfaceHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

class ValueInterfaceHandlerTest extends TestCase
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        $handler = new ValueInterfaceHandler();
        $handler->set(StringValue::class);
        $handler->set(TranslatableStringValue::class);
        $handler->set(StringCollectionValue::class);

        $this->serializer = SerializerBuilder::create()
            ->configureHandlers(function (HandlerRegistry $handlerRegistry) use ($handler) {
                $handlerRegistry->registerSubscribingHandler($handler);
            })
            ->build();
    }

    public function testConfiguration(): void
    {
        $configurations = ValueInterfaceHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            self::assertArrayHasKey('direction', $configuration);
            self::assertArrayHasKey('type', $configuration);
            self::assertArrayHasKey('format', $configuration);
            self::assertArrayHasKey('method', $configuration);
        }
    }

    public function testDeserializeStringValue(): void
    {
        $testValue = '{"type":"string","value":"test_value"}';

        /** @var ValueInterface $result */
        $result = $this->serializer->deserialize($testValue, ValueInterface::class, 'json');

        self::assertInstanceOf(StringValue::class, $result);
        self::assertEquals(StringValue::TYPE, $result->getType());
        self::assertEquals([null => 'test_value'], $result->getValue());
    }

    public function testDeserializeTranslatableStringValue(): void
    {
        $testValue = '{"type":"translation","value":{"translations":{"pl_PL":"test","en_GB":"test"}}}';

        /** @var ValueInterface $result */
        $result = $this->serializer->deserialize($testValue, ValueInterface::class, 'json');

        self::assertInstanceOf(TranslatableStringValue::class, $result);
        self::assertEquals(TranslatableStringValue::TYPE, $result->getType());
        self::assertEquals(['pl_PL' => 'test', 'en_GB' => 'test'], $result->getValue());
    }

    public function testDeserializeCollectionValue(): void
    {
        $testValue = '{"type":"collection","value":{"pl_PL":"test","en_GB":"test"}}';

        /** @var ValueInterface $result */
        $result = $this->serializer->deserialize($testValue, ValueInterface::class, 'json');

        self::assertInstanceOf(StringCollectionValue::class, $result);
        self::assertEquals(StringCollectionValue::TYPE, $result->getType());
        self::assertIsArray($result->getValue());
        self::assertEquals(['pl_PL' => 'test', 'en_GB' => 'test'], $result->getValue());
    }
}
