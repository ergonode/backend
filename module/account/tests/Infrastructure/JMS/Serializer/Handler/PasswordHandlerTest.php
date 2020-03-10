<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
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
    private const DECODED = 'test_value';
    private const ENCODED = 'dGVzdF92YWx1ZQ==';

    /**
     * @var PasswordHandler
     */
    private PasswordHandler $handler;

    /**
     * @var SerializationVisitorInterface
     */
    private SerializationVisitorInterface $serializerVisitor;

    /**
     * @var DeserializationVisitorInterface
     */
    private DeserializationVisitorInterface $deserializerVisitor;

    /**
     * @var Context
     */
    private Context $context;

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
        /** @var Password|MockObject $code */
        $code = $this->createMock(Password::class);
        $code->method('getValue')->willReturn(self::DECODED);
        $result = $this->handler->serialize($this->serializerVisitor, $code, [], $this->context);

        $this->assertEquals(self::ENCODED, $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $result = $this->handler->deserialize($this->deserializerVisitor, self::ENCODED, [], $this->context);

        $this->assertInstanceOf(Password::class, $result);
        $this->assertEquals(self::DECODED, $result->getValue());
    }
}
