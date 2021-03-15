<?php
/*
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\Channel\Infrastructure\JMS\Serializer\Handler\ExportLineIdHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

class ExportLineIdHandlerTest extends TestCase
{
    private ExportLineIdHandler $handler;

    private SerializationVisitorInterface $serializerVisitor;

    private DeserializationVisitorInterface $deserializerVisitor;

    private Context $context;

    protected function setUp(): void
    {
        $this->handler = new ExportLineIdHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    public function testConfiguration(): void
    {
        $configurations = ExportLineIdHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            self::assertArrayHasKey('direction', $configuration);
            self::assertArrayHasKey('type', $configuration);
            self::assertArrayHasKey('format', $configuration);
            self::assertArrayHasKey('method', $configuration);
        }
    }

    public function testSerialize(): void
    {
        $id = ExportLineId::generate();
        $result = $this->handler->serialize($this->serializerVisitor, $id, [], $this->context);

        self::assertEquals($id->getValue(), $result);
    }

    public function testDeserialize(): void
    {
        $id = ExportLineId::generate();
        $result = $this->handler->deserialize($this->deserializerVisitor, $id->getValue(), [], $this->context);

        self::assertEquals($id, $result);
    }
}
