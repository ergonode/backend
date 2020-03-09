<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Tests\Domain\Factory;

use Ergonode\Designer\Domain\Factory\TemplateElementFactory;
use Ergonode\Designer\Domain\Resolver\TemplateElementTypeResolver;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class TemplateElementFactoryTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private MockObject $serializer;

    /**
     * @var TemplateElementTypeResolver
     */
    private TemplateElementTypeResolver $resolver;

    /**
     */
    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $templateElement = $this->createMock(TemplateElementPropertyInterface::class);
        $this->serializer->method('deserialize')->willReturn($templateElement);
        $this->resolver = $this->createMock(TemplateElementTypeResolver::class);
        $this->resolver->method('resolve')->willReturn('Resolver type');
    }

    /**
     */
    public function testFactoryCreateTemplateElement(): void
    {
        /** @var Position|MockObject $position */
        $position = $this->createMock(Position::class);
        /** @var Size|MockObject $size */
        $size = $this->createMock(Size::class);

        /**
         */
        $type = 'Any Type';
        $factory = new TemplateElementFactory($this->resolver, $this->serializer);
        $element = $factory->create($position, $size, $type);

        $this->assertEquals($position, $element->getPosition());
        $this->assertEquals($type, $element->getType());
        $this->assertNotEmpty($element->getProperties());
    }
}
