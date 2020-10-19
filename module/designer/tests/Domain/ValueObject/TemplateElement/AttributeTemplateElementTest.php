<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Tests\Domain\ValueObject\TemplateElement;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeTemplateElementTest extends TestCase
{
    /**
     */
    public function testElementCreation(): void
    {
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);

        $element = new AttributeTemplateElementProperty($attributeId, true);
        self::assertEquals($attributeId, $element->getAttributeId());
        self::assertTrue($element->isRequired());
        self::assertEquals(AttributeTemplateElementProperty::VARIANT, $element->getVariant());
    }
}
