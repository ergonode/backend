<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Workflow\Infrastructure\JMS\Serializer\Handler\WorkflowIdHandler;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
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
    private $handler;

    /**
     * @var SerializationVisitorInterface
     */
    private $serializationVisitor;

    /**
     * @var DeserializationVisitorInterface
     */
    private $deserializationVisitor;

    /**
     * @var Context
     */
    private $context;

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
        $id = WorkflowId::generate();
        $result = $this->handler->serialize($this->serializationVisitor, $id, [], $this->context);

        $this->assertEquals($id->getValue(), $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $id = WorkflowId::generate();
        $result = $this->handler->deserialize($this->deserializationVisitor, $id->getValue(), [], $this->context);

        $this->assertEquals($id, $result->getValue());
    }
}
