<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\SharedKernel\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\SharedKernel\Infrastructure\JMS\Serializer\Handler\TransformerIdHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class TransformerIdHandlerTest extends TestCase
{
    /**
     * @var TransformerIdHandler
     */
    private TransformerIdHandler $handler;

    /**
     * @var DeserializationVisitorInterface
     */
    private DeserializationVisitorInterface $deserializationVisitor;

    /**
     * @var SerializationVisitorInterface
     */
    private SerializationVisitorInterface $serializationVisitor;

    /**
     * @var Context
     */
    private Context $context;

    /**
     */
    protected function setUp(): void
    {
        $this->handler = new TransformerIdHandler();
        $this->serializationVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializationVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = TransformerIdHandler::getSubscribingMethods();
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
        /** @var MockObject|TransformerId $id */
        $id = $this->createMock(TransformerId::class);
        $id->method('getValue')->willReturn(Uuid::NIL);
        $result = $this->handler->serialize($this->serializationVisitor, $id, [], $this->context);

        $this->assertEquals($id->getValue(), $result);
    }

    /**
     * @throws \Exception
     */
    public function testDeserialize(): void
    {
        $id = Uuid::NIL;
        $result = $this->handler->deserialize($this->deserializationVisitor, $id, [], $this->context);

        $this->assertEquals($id, $result->getValue());
    }
}
