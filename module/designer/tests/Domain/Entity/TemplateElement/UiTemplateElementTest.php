<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\Entity\TemplateElement;

use PHPUnit\Framework\TestCase;
use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;

class UiTemplateElementTest extends TestCase
{
    public function testUITemplateCreation(): void
    {
        $label = 'Any Element label';
        $position = $this->createMock(Position::class);
        $size = $this->createMock(Size::class);

        $element = new UiTemplateElement($position, $size, $label);
        $this->assertSame($position, $element->getPosition());
        $this->assertSame($size, $element->getSize());
        $this->assertEquals($label, $element->getLabel());
        $this->assertEquals(UiTemplateElement::TYPE, $element->getType());
    }
}
