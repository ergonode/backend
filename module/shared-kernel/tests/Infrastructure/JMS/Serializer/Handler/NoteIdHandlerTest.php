<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\SharedKernel\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\SharedKernel\Infrastructure\JMS\Serializer\Handler\CommentIdHandler;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class NoteIdHandlerTest extends TestCase
{
    /**
     * @var CommentIdHandler
     */
    private CommentIdHandler $handler;

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
        $this->handler = new CommentIdHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = CommentIdHandler::getSubscribingMethods();
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
        $id = CommentId::generate();
        $result = $this->handler->serialize($this->serializerVisitor, $id, [], $this->context);

        self::assertEquals($id->getValue(), $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $id = CommentId::generate();
        $result = $this->handler->deserialize($this->deserializerVisitor, $id->getValue(), [], $this->context);

        self::assertEquals($id, $result);
    }
}
