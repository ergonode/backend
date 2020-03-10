<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Value\Infrastructure\JMS\Serializer\Handler\ValueInterfaceHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class ValueInterfaceHandlerTest extends TestCase
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     */
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

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = ValueInterfaceHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            $this->assertArrayHasKey('direction', $configuration);
            $this->assertArrayHasKey('type', $configuration);
            $this->assertArrayHasKey('format', $configuration);
            $this->assertArrayHasKey('method', $configuration);
        }
    }

    /**
     */
    public function testDeserializeStringValue(): void
    {
        $testValue = '{"type":"string","value":"test_value"}';

        /** @var ValueInterface $result */
        $result = $this->serializer->deserialize($testValue, ValueInterface::class, 'json');

        $this->assertInstanceOf(StringValue::class, $result);
        $this->assertEquals(StringValue::TYPE, $result->getType());
        $this->assertEquals('test_value', $result->getValue());
    }

    /**
     */
    public function testDeserializeTranslatableStringValue(): void
    {
        $testValue = '{"type":"translation","value":{"translations":{"PL":"test","EN":"test"}}}';

        /** @var ValueInterface $result */
        $result = $this->serializer->deserialize($testValue, ValueInterface::class, 'json');

        $this->assertInstanceOf(TranslatableStringValue::class, $result);
        $this->assertEquals(TranslatableStringValue::TYPE, $result->getType());
        $this->assertInstanceOf(TranslatableString::class, $result->getValue());
        $this->assertEquals(['PL' => 'test', 'EN' => 'test'], $result->getValue()->getTranslations());
    }

    /**
     */
    public function testDeserializeCollectionValue(): void
    {
        $testValue = '{"type":"string_collection","value":["test","test"]}';

        /** @var ValueInterface $result */
        $result = $this->serializer->deserialize($testValue, ValueInterface::class, 'json');

        $this->assertInstanceOf(StringCollectionValue::class, $result);
        $this->assertEquals(StringCollectionValue::TYPE, $result->getType());
        $this->assertIsArray($result->getValue());
        $this->assertEquals(['test', 'test'], $result->getValue());
    }
}
