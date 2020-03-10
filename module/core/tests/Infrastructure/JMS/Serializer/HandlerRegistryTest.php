<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Core\Infrastructure\JMS\Serializer;

use Ergonode\Core\Infrastructure\JMS\Serializer\HandlerRegistry;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 */
class HandlerRegistryTest extends TestCase
{
    /**
     * @var HandlerRegistryInterface
     */
    private HandlerRegistryInterface $registry;

    /**
     */
    protected function setUp(): void
    {
        $this->registry = $this->createMock(HandlerRegistryInterface::class);
    }

    /**
     */
    public function testGetHandler(): void
    {
        $direction = 2;
        $typeName = 'type';
        $format = 'format';
        $container = $this->createMock(ContainerInterface::class);
        $handlers = [];
        $this->registry->expects($this->at(0))->method('getHandler')->willReturn($this->createMock(\stdClass::class));
        $this->registry->expects($this->at(1))->method('getHandler')->willReturn(null);
        $handlerRegistry1 = new HandlerRegistry($container, $handlers, $this->registry);
        $this->assertInstanceOf(\stdClass::class, $handlerRegistry1->getHandler($direction, $typeName, $format));
        $handlerRegistry2 = new HandlerRegistry($container, $handlers, $this->registry);
        $this->assertNull($handlerRegistry2->getHandler($direction, $typeName, $format));
    }
}
