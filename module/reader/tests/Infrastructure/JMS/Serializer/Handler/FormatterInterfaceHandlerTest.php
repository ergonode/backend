<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Reader\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Reader\Infrastructure\Formatter\EncodingFormatter;
use Ergonode\Reader\Infrastructure\Formatter\ReplaceFormatter;
use Ergonode\Reader\Infrastructure\FormatterInterface;
use Ergonode\Reader\Infrastructure\JMS\Serializer\Handler\FormatterInterfaceHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

class FormatterInterfaceHandlerTest extends TestCase
{
    private SerializerInterface $serializer;

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

    public function testConfiguration(): void
    {
        $configurations = FormatterInterfaceHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            $this->assertArrayHasKey('direction', $configuration);
            $this->assertArrayHasKey('type', $configuration);
            $this->assertArrayHasKey('format', $configuration);
            $this->assertArrayHasKey('method', $configuration);
        }
    }

    public function testDeserializeEncodingFormatter(): void
    {
        $testValue = '{"type":"encoding","encoding":"test_value"}';

        /** @var FormatterInterface $result */
        $result = $this->serializer->deserialize($testValue, FormatterInterface::class, 'json');

        $this->assertInstanceOf(EncodingFormatter::class, $result);
        $this->assertEquals(EncodingFormatter::TYPE, $result->getType());
    }

    public function testDeserializeReplaceFormatter(): void
    {
        $testValue = '{"type":"replace","from":"test_value","to":"test_value"}';

        /** @var FormatterInterface $result */
        $result = $this->serializer->deserialize($testValue, FormatterInterface::class, 'json');

        $this->assertInstanceOf(ReplaceFormatter::class, $result);
        $this->assertEquals(ReplaceFormatter::TYPE, $result->getType());
    }
}
