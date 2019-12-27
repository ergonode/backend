<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Tests\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\UiTemplateElementProperty;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\Designer\Infrastructure\JMS\Serializer\Handler\TemplateElementPropertyInterfaceHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class TemplateElementPropertyInterfaceHandlerTest extends TestCase
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        $handler = new TemplateElementPropertyInterfaceHandler('VARIANT');
        $handler->set(AttributeTemplateElementProperty::class);
        $handler->set(UiTemplateElementProperty::class);

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
        $configurations = TemplateElementPropertyInterfaceHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            $this->assertArrayHasKey('direction', $configuration);
            $this->assertArrayHasKey('type', $configuration);
            $this->assertArrayHasKey('format', $configuration);
            $this->assertArrayHasKey('method', $configuration);
        }
    }

    /**
     */
    public function testDeserializeAttributeTemplateElementProperty(): void
    {
        $testValue =
            '{"variant":"attribute","attribute_id":{"value":"c33ad15f-9719-53c5-bba7-22ebee12855e"},"required":false}';

        /** @var AttributeTemplateElementProperty $result */
        $result = $this->serializer->deserialize($testValue, TemplateElementPropertyInterface::class, 'json');

        $this->assertInstanceOf(AttributeTemplateElementProperty::class, $result);
        $this->assertEquals(AttributeTemplateElementProperty::VARIANT, $result->getVariant());
    }

    /**
     */
    public function testDeserializeUiTemplateElementProperty(): void
    {
        $testValue = '{"variant":"ui","label":"test_label"}';

        /** @var UiTemplateElementProperty $result */
        $result = $this->serializer->deserialize($testValue, TemplateElementPropertyInterface::class, 'json');

        $this->assertInstanceOf(UiTemplateElementProperty::class, $result);
        $this->assertEquals(UiTemplateElementProperty::VARIANT, $result->getVariant());
    }
}
