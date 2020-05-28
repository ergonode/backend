<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Exporter\Infrastructure\JMS\Serializer\Handler\ExportStatusHandler;
use PHPUnit\Framework\TestCase;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Context;
use Ergonode\Exporter\Domain\ValueObject\ExportStatus;

/**
 */
class ExportStatusHandlerTest extends TestCase
{
    /**
     * @var ExportStatusHandler
     */
    private ExportStatusHandler $handler;

    /**
     * @var SerializationVisitorInterface
     */
    private SerializationVisitorInterface $serializerVisitor;

    /**
     * @var DeserializationVisitorInterface
     */
    private DeserializationVisitorInterface $deserializerVisitor;

    /**
     * @var Context
     */
    private Context $context;

    /**
     */
    protected function setUp(): void
    {
        $this->handler = new ExportStatusHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = ExportStatusHandler::getSubscribingMethods();
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
        $testValue = ExportStatus::STOPPED;
        $code = new ExportStatus($testValue);
        $result = $this->handler->serialize($this->serializerVisitor, $code, [], $this->context);

        $this->assertEquals($testValue, $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $testValue = ExportStatus::STOPPED;
        $result = $this->handler->deserialize($this->deserializerVisitor, $testValue, [], $this->context);

        $this->assertEquals($testValue, (string) $result);
    }
}
