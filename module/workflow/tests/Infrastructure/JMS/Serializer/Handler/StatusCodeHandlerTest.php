<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Workflow\Infrastructure\JMS\Serializer\Handler\StatusCodeHandler;
use PHPUnit\Framework\TestCase;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Context;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;

class StatusCodeHandlerTest extends TestCase
{
    private StatusCodeHandler $handler;

    private SerializationVisitorInterface $serializerVisitor;

    private DeserializationVisitorInterface $deserializerVisitor;

    private Context $context;

    protected function setUp(): void
    {
        $this->handler = new StatusCodeHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    public function testConfiguration(): void
    {
        $configurations = StatusCodeHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            self::assertArrayHasKey('direction', $configuration);
            self::assertArrayHasKey('type', $configuration);
            self::assertArrayHasKey('format', $configuration);
            self::assertArrayHasKey('method', $configuration);
        }
    }

    public function testSerialize(): void
    {
        $testValue = 'test_value';
        $code = new StatusCode($testValue);
        $result = $this->handler->serialize($this->serializerVisitor, $code, [], $this->context);

        self::assertEquals($testValue, $result);
    }

    public function testDeserialize(): void
    {
        $testValue = 'test_value';
        $result = $this->handler->deserialize($this->deserializerVisitor, $testValue, [], $this->context);

        self::assertEquals($testValue, $result->getValue());
    }
}
