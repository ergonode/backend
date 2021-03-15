<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\Entity\TemplateElement;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;

class AttributeTemplateElementTest extends TestCase
{
    public function testElementCreation(): void
    {
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        $position = $this->createMock(Position::class);
        $size = $this->createMock(Size::class);

        $element = new AttributeTemplateElement($position, $size, $attributeId, true);
        $this->assertSame($position, $element->getPosition());
        $this->assertSame($size, $element->getSize());
        $this->assertEquals($attributeId, $element->getAttributeId());
        $this->assertTrue($element->isRequired());
        $this->assertEquals(AttributeTemplateElement::TYPE, $element->getType());
    }
}
