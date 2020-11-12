<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Importer\Infrastructure\Converter\ConstConverter;
use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Importer\Infrastructure\Converter\DateConverter;
use Ergonode\Importer\Infrastructure\Converter\JoinConverter;
use Ergonode\Importer\Infrastructure\Converter\MappingConverter;
use Ergonode\Importer\Infrastructure\Converter\SlugConverter;
use Ergonode\Importer\Infrastructure\Converter\TextConverter;
use Ergonode\Importer\Infrastructure\JMS\Serializer\Handler\ConverterInterfaceHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

class ConverterInterfaceHandlerTest extends TestCase
{
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
            ->configureHandlers(function (HandlerRegistry $handlerRegistry) use ($handler): void {
                $handlerRegistry->registerSubscribingHandler($handler);
            })
            ->build();
    }

    public function testConfiguration(): void
    {
        $configurations = ConverterInterfaceHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            $this->assertArrayHasKey('direction', $configuration);
            $this->assertArrayHasKey('type', $configuration);
            $this->assertArrayHasKey('format', $configuration);
            $this->assertArrayHasKey('method', $configuration);
        }
    }

    public function testDeserializeTextConverter(): void
    {
        $testValue = '{"type":"text","field":"test_field"}';

        /** @var ConverterInterface $result */
        $result = $this->serializer->deserialize($testValue, ConverterInterface::class, 'json');

        $this->assertInstanceOf(TextConverter::class, $result);
        $this->assertEquals(TextConverter::TYPE, $result->getType());
    }

    public function testDeserializeSlugConverter(): void
    {
        $testValue = '{"type":"slug","field":"test_field"}';

        /** @var ConverterInterface $result */
        $result = $this->serializer->deserialize($testValue, ConverterInterface::class, 'json');

        $this->assertInstanceOf(SlugConverter::class, $result);
        $this->assertEquals(SlugConverter::TYPE, $result->getType());
    }

    public function testDeserializeJoinConverter(): void
    {
        $testValue = '{"type":"join","pattern":"<%s>"}';

        /** @var ConverterInterface $result */
        $result = $this->serializer->deserialize($testValue, ConverterInterface::class, 'json');

        $this->assertInstanceOf(JoinConverter::class, $result);
        $this->assertEquals(JoinConverter::TYPE, $result->getType());
    }

    public function testDeserializeDateConverter(): void
    {
        $testValue = '{"type":"date","format":"Y-m-d","field":"test_field"}';

        /** @var ConverterInterface $result */
        $result = $this->serializer->deserialize($testValue, ConverterInterface::class, 'json');

        $this->assertInstanceOf(DateConverter::class, $result);
        $this->assertEquals(DateConverter::TYPE, $result->getType());
    }

    public function testDeserializeConstConverter(): void
    {
        $testValue = '{"type":"const","value":"CONST"}';

        /** @var ConverterInterface $result */
        $result = $this->serializer->deserialize($testValue, ConverterInterface::class, 'json');

        $this->assertInstanceOf(ConstConverter::class, $result);
        $this->assertEquals(ConstConverter::TYPE, $result->getType());
    }

    public function testDeserializeMappingConverter(): void
    {
        $testValue = '{"type":"mapping","map":{"field":"value"},"field":"test_field"}';

        /** @var ConverterInterface $result */
        $result = $this->serializer->deserialize($testValue, ConverterInterface::class, 'json');

        $this->assertInstanceOf(MappingConverter::class, $result);
        $this->assertEquals(MappingConverter::TYPE, $result->getType());
    }
}
