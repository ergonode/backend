<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Infrastructure\JMS\Serializer\Handler\AttributeCodeHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeCodeHandlerTest extends TestCase
{
    /**
     * @var AttributeCodeHandler
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
        $this->handler = new AttributeCodeHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = AttributeCodeHandler::getSubscribingMethods();
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
        $code = new AttributeCode($testValue);
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
