<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Transformer\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Transformer\Domain\ValueObject\ProcessorStatus;
use Ergonode\Transformer\Infrastructure\JMS\Serializer\Handler\ProcessorStatusHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

class ProcessorStatusHandlerTest extends TestCase
{
    /**
     * @var ProcessorStatusHandler
     */
    private $handler;

    /**
     * @var DeserializationVisitorInterface
     */
    private $deserializationVisitor;

    /**
     * @var SerializationVisitorInterface
     */
    private $serializationVisitor;

    /**
     * @var Context
     */
    private $context;

    /**
     */
    protected function setUp(): void
    {
        $this->handler = new ProcessorStatusHandler();
        $this->serializationVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializationVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = ProcessorStatusHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            $this->assertArrayHasKey('direction', $configuration);
            $this->assertArrayHasKey('type', $configuration);
            $this->assertArrayHasKey('format', $configuration);
            $this->assertArrayHasKey('method', $configuration);
        }
    }

    /**
     * @throws \Exception
     */
    public function testSerialize(): void
    {
        $id = $this->createMock(ProcessorStatus::class);
        $id->method('__toString')->willReturn(ProcessorStatus::CREATED);
        $result = $this->handler->serialize($this->serializationVisitor, $id, [], $this->context);

        $this->assertEquals($id, $result);
    }

    /**
     * @throws \Exception
     */
    public function testDeserialize(): void
    {
        $id = ProcessorStatus::CREATED;
        $result = $this->handler->deserialize($this->deserializationVisitor, $id, [], $this->context);

        $this->assertEquals($id, $result);
    }
}
