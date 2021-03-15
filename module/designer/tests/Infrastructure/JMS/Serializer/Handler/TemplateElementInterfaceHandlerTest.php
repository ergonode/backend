<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Infrastructure\JMS\Serializer\Handler;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;
use Ergonode\Designer\Infrastructure\JMS\Serializer\Handler\TemplateElementInterfaceHandler;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;
use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;

class TemplateElementInterfaceHandlerTest extends TestCase
{
    public SerializerInterface $serializer;

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        $handler = new TemplateElementInterfaceHandler();
        $handler->set(AttributeTemplateElement::class);
        $handler->set(UiTemplateElement::class);

        $this->serializer = SerializerBuilder::create()
            ->configureHandlers(function (HandlerRegistry $handlerRegistry) use ($handler): void {
                $handlerRegistry->registerSubscribingHandler($handler);
            })
            ->build();
    }

    public function testConfiguration(): void
    {
        $configurations = TemplateElementInterfaceHandler::getSubscribingMethods();
        foreach ($configurations as $configuration) {
            $this->assertArrayHasKey('direction', $configuration);
            $this->assertArrayHasKey('type', $configuration);
            $this->assertArrayHasKey('format', $configuration);
            $this->assertArrayHasKey('method', $configuration);
        }
    }
}
