<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\BatchAction\Infrastructure\JMS\Serializer\Handler\BatchActionIdHandler;
use PHPUnit\Framework\TestCase;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Context;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;

class BatchActionIdHandlerTest extends TestCase
{
    private BatchActionIdHandler $handler;

    private SerializationVisitorInterface $serializerVisitor;

    private DeserializationVisitorInterface $deserializerVisitor;

    private Context $context;

    protected function setUp(): void
    {
        $this->handler = new BatchActionIdHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    public function testConfiguration(): void
    {
        $configurations = BatchActionIdHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            $this::assertArrayHasKey('direction', $configuration);
            $this::assertArrayHasKey('type', $configuration);
            $this::assertArrayHasKey('format', $configuration);
            $this::assertArrayHasKey('method', $configuration);
        }
    }

    public function testSerialize(): void
    {
        $valueObject = BatchActionId::generate();
        $result = $this->handler->serialize($this->serializerVisitor, $valueObject, [], $this->context);

        $this::assertEquals($valueObject->getValue(), $result);
    }

    public function testDeserialize(): void
    {
        $valueObject = BatchActionId::generate();
        $result = $this->handler->deserialize($this->deserializerVisitor, $valueObject->getValue(), [], $this->context);

        $this::assertEquals($valueObject, $result);
    }
}
