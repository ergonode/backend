<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Multimedia\Infrastructure\JMS\Serializer\Handler\HashHandler;
use PHPUnit\Framework\TestCase;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Context;
use Ergonode\Multimedia\Domain\ValueObject\Hash;

/**
 */
class HashHandlerTest extends TestCase
{
    /**
     * @var HashHandler
     */
    private HashHandler $handler;

    /**
     * @var SerializationVisitorInterface
     */
    private SerializationVisitorInterface $serializationVisitor;

    /**
     * @var DeserializationVisitorInterface
     */
    private DeserializationVisitorInterface $deserializationVisitor;

    /**
     * @var Context
     */
    private Context $context;

    /**
     */
    protected function setUp(): void
    {
        $this->handler = new HashHandler();
        $this->serializationVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializationVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = HashHandler::getSubscribingMethods();
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
        $testValue = 'abcdefghabcdefghabcdefghabcdefghabcdefghabcdefgh';
        $value = $this->createMock(Hash::class);
        $value->method('getValue')->willReturn($testValue);
        $result = $this->handler->serialize($this->serializationVisitor, $value, [], $this->context);

        $this->assertEquals($testValue, $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $testValue = 'abcdefghabcdefghabcdefghabcdefghabcdefghabcdefgh';
        $result = $this->handler->deserialize($this->deserializationVisitor, $testValue, [], $this->context);

        $this->assertEquals($testValue, $result->getValue());
    }
}
