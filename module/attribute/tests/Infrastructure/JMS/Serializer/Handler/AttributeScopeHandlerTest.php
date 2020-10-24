<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Attribute\Infrastructure\JMS\Serializer\Handler\AttributeScopeHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

class AttributeScopeHandlerTest extends TestCase
{
    private AttributeScopeHandler $handler;

    private SerializationVisitorInterface $serializerVisitor;

    private DeserializationVisitorInterface $deserializerVisitor;

    private Context $context;

    protected function setUp(): void
    {
        $this->handler = new AttributeScopeHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    public function testConfiguration(): void
    {
        $configurations = AttributeScopeHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            $this->assertArrayHasKey('direction', $configuration);
            $this->assertArrayHasKey('type', $configuration);
            $this->assertArrayHasKey('format', $configuration);
            $this->assertArrayHasKey('method', $configuration);
        }
    }

    public function testSerialize(): void
    {
        $testValue = 'local';
        $code = new AttributeScope($testValue);
        $result = $this->handler->serialize($this->serializerVisitor, $code, [], $this->context);

        $this->assertEquals($testValue, $result);
    }

    public function testDeserialize(): void
    {
        $testValue = 'local';
        $result = $this->handler->deserialize($this->deserializerVisitor, $testValue, [], $this->context);

        $this->assertEquals($testValue, $result->getValue());
    }
}
