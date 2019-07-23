<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Account\Infrastructure\JMS\Serializer\Handler\PasswordHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class PasswordHandlerTest extends TestCase
{
    /**
     * @var PasswordHandler
     */
    private $handler;

    /**
     * @var SerializationVisitorInterface
     */
    private $serializerVisitor;

    /**
     * @var DeserializationVisitorInterface
     */
    private $deserializerVisitor;

    /**
     * @var Context
     */
    private $context;

    /**
     */
    protected function setUp(): void
    {
        $this->handler = new PasswordHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = PasswordHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            $this->assertArrayHasKey('direction', $configuration);
            $this->assertArrayHasKey('type', $configuration);
            $this->assertArrayHasKey('format', $configuration);
            $this->assertArrayHasKey('method', $configuration);
        }
    }

    /**
     */
    public function testSerialize(): void
    {
        $testValue = 'test_value';
        /** @var Password|MockObject $code */
        $code = $this->createMock(Password::class);
        $code->method('getValue')->willReturn($testValue);
        $result = $this->handler->serialize($this->serializerVisitor, $code, [], $this->context);

        $this->assertEquals($testValue, $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $testValue = 'test_value';
        $result = $this->handler->deserialize($this->deserializerVisitor, $testValue, [], $this->context);

        $this->assertEquals($testValue, $result->getValue());
    }
}
