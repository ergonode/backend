<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Reader\Infrastructure\Formatter\EncodingFormatter;
use Ergonode\Reader\Infrastructure\Formatter\ReplaceFormatter;
use Ergonode\Reader\Infrastructure\FormatterInterface;
use Ergonode\Reader\Infrastructure\JMS\Serializer\Handler\FormatterInterfaceHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class FormatterInterfaceHandlerTest extends TestCase
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     */
    protected function setUp(): void
    {
        $handler = new FormatterInterfaceHandler();
        $handler->set(EncodingFormatter::class);
        $handler->set(ReplaceFormatter::class);

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
        $configurations = FormatterInterfaceHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            self::assertArrayHasKey('direction', $configuration);
            self::assertArrayHasKey('type', $configuration);
            self::assertArrayHasKey('format', $configuration);
            self::assertArrayHasKey('method', $configuration);
        }
    }

    /**
     */
    public function testDeserializeEncodingFormatter(): void
    {
        $testValue = '{"type":"encoding","encoding":"test_value"}';

        /** @var FormatterInterface $result */
        $result = $this->serializer->deserialize($testValue, FormatterInterface::class, 'json');

        self::assertInstanceOf(EncodingFormatter::class, $result);
        self::assertEquals(EncodingFormatter::TYPE, $result->getType());
    }

    /**
     */
    public function testDeserializeReplaceFormatter(): void
    {
        $testValue = '{"type":"replace","from":"test_value","to":"test_value"}';

        /** @var FormatterInterface $result */
        $result = $this->serializer->deserialize($testValue, FormatterInterface::class, 'json');

        self::assertInstanceOf(ReplaceFormatter::class, $result);
        self::assertEquals(ReplaceFormatter::TYPE, $result->getType());
    }
}
