<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Transformer\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Transformer\Domain\Entity\ProcessorId;
use Ergonode\Transformer\Infrastructure\JMS\Serializer\Handler\ProcessorIdHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class ProcessorIdHandlerTest extends TestCase
{
    /**
     * @var ProcessorIdHandler
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
        $this->handler = new ProcessorIdHandler();
        $this->serializationVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializationVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = ProcessorIdHandler::getSubscribingMethods();
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
        $id = ProcessorId::generate();
        $result = $this->handler->serialize($this->serializationVisitor, $id, [], $this->context);

        $this->assertEquals($id->getValue(), $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $id = ProcessorId::generate();
        $result = $this->handler->deserialize($this->deserializationVisitor, $id->getValue(), [], $this->context);

        $this->assertEquals($id, $result);
    }
}
