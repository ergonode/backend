<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Transformer\Infrastructure\Converter\ConstConverter;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Converter\DateConverter;
use Ergonode\Transformer\Infrastructure\Converter\JoinConverter;
use Ergonode\Transformer\Infrastructure\Converter\MappingConverter;
use Ergonode\Transformer\Infrastructure\Converter\SlugConverter;
use Ergonode\Transformer\Infrastructure\Converter\SplitConverter;
use Ergonode\Transformer\Infrastructure\Converter\TextConverter;
use Ergonode\Transformer\Infrastructure\JMS\Serializer\Handler\ConverterInterfaceHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class ConverterInterfaceHandlerTest extends TestCase
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        $handler = new ConverterInterfaceHandler();
        $handler->set(TextConverter::class);
        $handler->set(SlugConverter::class);
        $handler->set(JoinConverter::class);
        $handler->set(DateConverter::class);
        $handler->set(ConstConverter::class);
        $handler->set(MappingConverter::class);

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
        $configurations = ConverterInterfaceHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            self::assertArrayHasKey('direction', $configuration);
            self::assertArrayHasKey('type', $configuration);
            self::assertArrayHasKey('format', $configuration);
            self::assertArrayHasKey('method', $configuration);
        }
    }

    /**
     */
    public function testDeserializeTextConverter(): void
    {
        $testValue = '{"type":"text","field":"test_field"}';

        /** @var ConverterInterface $result */
        $result = $this->serializer->deserialize($testValue, ConverterInterface::class, 'json');

        self::assertInstanceOf(TextConverter::class, $result);
        self::assertEquals(TextConverter::TYPE, $result->getType());
    }

    /**
     */
    public function testDeserializeSlugConverter(): void
    {
        $testValue = '{"type":"slug","field":"test_field"}';

        /** @var ConverterInterface $result */
        $result = $this->serializer->deserialize($testValue, ConverterInterface::class, 'json');

        self::assertInstanceOf(SlugConverter::class, $result);
        self::assertEquals(SlugConverter::TYPE, $result->getType());
    }

    /**
     */
    public function testDeserializeJoinConverter(): void
    {
        $testValue = '{"type":"join","pattern":"<%s>"}';

        /** @var ConverterInterface $result */
        $result = $this->serializer->deserialize($testValue, ConverterInterface::class, 'json');

        self::assertInstanceOf(JoinConverter::class, $result);
        self::assertEquals(JoinConverter::TYPE, $result->getType());
    }

    /**
     */
    public function testDeserializeDateConverter(): void
    {
        $testValue = '{"type":"date","format":"Y-m-d","field":"test_field"}';

        /** @var ConverterInterface $result */
        $result = $this->serializer->deserialize($testValue, ConverterInterface::class, 'json');

        self::assertInstanceOf(DateConverter::class, $result);
        self::assertEquals(DateConverter::TYPE, $result->getType());
    }

    /**
     */
    public function testDeserializeConstConverter(): void
    {
        $testValue = '{"type":"const","value":"CONST"}';

        /** @var ConverterInterface $result */
        $result = $this->serializer->deserialize($testValue, ConverterInterface::class, 'json');

        self::assertInstanceOf(ConstConverter::class, $result);
        self::assertEquals(ConstConverter::TYPE, $result->getType());
    }

    /**
     */
    public function testDeserializeMappingConverter(): void
    {
        $testValue = '{"type":"mapping","map":{"field":"value"},"field":"test_field"}';

        /** @var ConverterInterface $result */
        $result = $this->serializer->deserialize($testValue, ConverterInterface::class, 'json');

        self::assertInstanceOf(MappingConverter::class, $result);
        self::assertEquals(MappingConverter::TYPE, $result->getType());
    }
}
