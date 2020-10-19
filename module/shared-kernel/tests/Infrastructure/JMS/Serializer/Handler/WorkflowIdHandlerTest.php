<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\SharedKernel\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\SharedKernel\Infrastructure\JMS\Serializer\Handler\WorkflowIdHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class WorkflowIdHandlerTest extends TestCase
{
    /**
     * @var WorkflowIdHandler
     */
    private WorkflowIdHandler $handler;

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
        $this->handler = new WorkflowIdHandler();
        $this->serializationVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializationVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = WorkflowIdHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            self::assertArrayHasKey('direction', $configuration);
            self::assertArrayHasKey('type', $configuration);
            self::assertArrayHasKey('format', $configuration);
            self::assertArrayHasKey('method', $configuration);
        }
    }

    /**
     */
    public function testSerialize(): void
    {
        $id = WorkflowId::generate();
        $result = $this->handler->serialize($this->serializationVisitor, $id, [], $this->context);

        self::assertEquals($id->getValue(), $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $id = WorkflowId::generate();
        $result = $this->handler->deserialize($this->deserializationVisitor, $id->getValue(), [], $this->context);

        self::assertEquals($id, $result->getValue());
    }
}
