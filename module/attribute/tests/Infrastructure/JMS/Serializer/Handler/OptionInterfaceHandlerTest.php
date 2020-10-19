<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Attribute\Infrastructure\JMS\Serializer\Handler\OptionInterfaceHandler;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class OptionInterfaceHandlerTest extends TestCase
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     */
    protected function setUp(): void
    {
        $handler = new OptionInterfaceHandler();
        $handler->set(StringOption::class);
        $handler->set(MultilingualOption::class);

        $this->serializer = SerializerBuilder::create()
            ->configureHandlers(function (HandlerRegistry $handlerRegistry) use ($handler) {
                $handlerRegistry->registerSubscribingHandler($handler);
            })
            ->build();
    }

    /**
     */
    public function testConfiguration(): void
    {
        $configurations = OptionInterfaceHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            self::assertArrayHasKey('direction', $configuration);
            self::assertArrayHasKey('type', $configuration);
            self::assertArrayHasKey('format', $configuration);
            self::assertArrayHasKey('method', $configuration);
        }
    }

    /**
     */
    public function testDeserializeStringOption(): void
    {
        $testValue = '{"type":"string","value":"test_value"}';

        /** @var ValueInterface $result */
        $result = $this->serializer->deserialize($testValue, OptionInterface::class, 'json');

        self::assertInstanceOf(StringOption::class, $result);
        self::assertEquals(StringOption::TYPE, $result->getType());
        self::assertEquals('test_value', $result->getValue());
        self::assertEquals('test_value', (string) $result);
    }

    /**
     */
    public function testDeserializeTranslatableMultilingualOption(): void
    {
        $testValue = '{"type":"translation","value":{"translations":{"pl_PL":"test","EN":"test"}}}';

        /** @var ValueInterface $result */
        $result = $this->serializer->deserialize($testValue, OptionInterface::class, 'json');

        self::assertInstanceOf(MultilingualOption::class, $result);
        self::assertEquals(MultilingualOption::TYPE, $result->getType());
        self::assertInstanceOf(TranslatableString::class, $result->getValue());
    }
}
