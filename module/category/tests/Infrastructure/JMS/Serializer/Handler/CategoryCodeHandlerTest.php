<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Category\Infrastructure\JMS\Serializer\Handler\CategoryCodeHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

class CategoryCodeHandlerTest extends TestCase
{
    private CategoryCodeHandler $handler;

    private SerializationVisitorInterface $serializerVisitor;

    private DeserializationVisitorInterface $deserializerVisitor;
    private Context $context;

    protected function setUp(): void
    {
        $this->handler = new CategoryCodeHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    public function testConfiguration(): void
    {
        $configurations = CategoryCodeHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            $this->assertArrayHasKey('direction', $configuration);
            $this->assertArrayHasKey('type', $configuration);
            $this->assertArrayHasKey('format', $configuration);
            $this->assertArrayHasKey('method', $configuration);
        }
    }

    public function testSerialize(): void
    {
        $id = new CategoryCode('Code');
        $result = $this->handler->serialize($this->serializerVisitor, $id, [], $this->context);

        $this->assertEquals($id->getValue(), $result);
    }

    public function testDeserialize(): void
    {
        $id = new CategoryCode('Code');
        $result = $this->handler->deserialize($this->deserializerVisitor, $id->getValue(), [], $this->context);

        $this->assertEquals($id, $result);
    }
}
