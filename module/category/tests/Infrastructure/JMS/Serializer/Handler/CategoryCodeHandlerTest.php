<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Category\Infrastructure\JMS\Serializer\Handler\CategoryCodeHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryCodeHandlerTest extends TestCase
{
    /**
     * @var CategoryCodeHandler
     */
    private CategoryCodeHandler $handler;

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
        $this->handler = new CategoryCodeHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = CategoryCodeHandler::getSubscribingMethods();
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
        $id = new CategoryCode('Code');
        $result = $this->handler->serialize($this->serializerVisitor, $id, [], $this->context);

        self::assertEquals($id->getValue(), $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $id = new CategoryCode('Code');
        $result = $this->handler->deserialize($this->deserializerVisitor, $id->getValue(), [], $this->context);

        self::assertEquals($id, $result);
    }
}
