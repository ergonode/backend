<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\Factory;

use Ergonode\Designer\Domain\Factory\TemplateElementFactory;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;
use Ergonode\Designer\Domain\Resolver\TemplateElementTypeResolver;

class TemplateElementFactoryTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private MockObject $serializer;

    private TemplateElementTypeResolver $resolver;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $templateElement = $this->createMock(TemplateElementInterface::class);
        $this->serializer->expects(self::once())->method('deserialize')->willReturn($templateElement);
        $this->resolver = $this->createMock(TemplateElementTypeResolver::class);
        $this->resolver->method('resolve')->willReturn('Resolver type');
    }

    public function testFactoryCreateTemplateElement(): void
    {
        /** @var Position|MockObject $position */
        $position = $this->createMock(Position::class);
        /** @var Size|MockObject $size */
        $size = $this->createMock(Size::class);

        $type = AttributeTemplateElement::TYPE;
        $factory = new TemplateElementFactory($this->resolver, $this->serializer);
        $factory->create($position, $size, $type);
    }
}
