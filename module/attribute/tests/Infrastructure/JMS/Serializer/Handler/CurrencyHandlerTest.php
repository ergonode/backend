<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\JMS\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Money\Currency;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Infrastructure\JMS\Serializer\Handler\CurrencyHandler;

/**
 */
class CurrencyHandlerTest extends TestCase
{
    /**
     * @var CurrencyHandler
     */
    private CurrencyHandler $handler;

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
        $this->handler = new CurrencyHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = CurrencyHandler::getSubscribingMethods();
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
        $id = new Currency('PLN');
        $result = $this->handler->serialize($this->serializerVisitor, $id, [], $this->context);

        $this->assertEquals($id->getCode(), $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $id = new Currency('PLN');
        $result = $this->handler->deserialize($this->deserializerVisitor, $id->getCode(), [], $this->context);

        $this->assertEquals($id, $result);
    }
}
