<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\SharedKernel\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;
use Ergonode\SharedKernel\Infrastructure\JMS\Serializer\Handler\LanguageIdHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class LanguageIdHandlerTest extends TestCase
{

    /**
     * @var LanguageIdHandler
     */
    private LanguageIdHandler $handler;

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
        $this->handler = new LanguageIdHandler();
        $this->serializerVisitor = $this->createMock(SerializationVisitorInterface::class);
        $this->deserializerVisitor = $this->createMock(DeserializationVisitorInterface::class);
        $this->context = $this->createMock(Context::class);
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = LanguageIdHandler::getSubscribingMethods();
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
        $id = LanguageId::generate();
        $result = $this->handler->serialize($this->serializerVisitor, $id, [], $this->context);

        self::assertEquals($id->getValue(), $result);
    }

    /**
     */
    public function testDeserialize(): void
    {
        $id = LanguageId::generate();
        $result = $this->handler->deserialize($this->deserializerVisitor, $id->getValue(), [], $this->context);

        self::assertEquals($id, $result);
    }
}
