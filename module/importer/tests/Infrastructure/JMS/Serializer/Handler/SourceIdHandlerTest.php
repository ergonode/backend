<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Importer\Infrastructure\JMS\Serializer\Handler\SourceIdHandler;
use PHPUnit\Framework\TestCase;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Context;
use Ramsey\Uuid\Uuid;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

class SourceIdHandlerTest extends TestCase
{
    private SourceIdHandler $handler;

    private SerializationVisitorInterface $serializerVisitor;

    private DeserializationVisitorInterface $deserializerVisitor;

    private Context $context;

    protected function setUp(): void
    {
        $this->handler = new SourceIdHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    public function testConfiguration(): void
    {
        $configurations = SourceIdHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            self::assertArrayHasKey('direction', $configuration);
            self::assertArrayHasKey('type', $configuration);
            self::assertArrayHasKey('format', $configuration);
            self::assertArrayHasKey('method', $configuration);
        }
    }

    public function testSerialize(): void
    {
        $testValue = Uuid::uuid4()->toString();
        $code = new SourceId($testValue);
        $result = $this->handler->serialize($this->serializerVisitor, $code, [], $this->context);

        self::assertEquals($testValue, $result);
    }

    public function testDeserialize(): void
    {
        $testValue = Uuid::uuid4()->toString();
        $result = $this->handler->deserialize($this->deserializerVisitor, $testValue, [], $this->context);

        self::assertEquals($testValue, (string) $result);
    }
}
