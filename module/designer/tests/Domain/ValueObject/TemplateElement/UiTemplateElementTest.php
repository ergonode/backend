<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\ValueObject\TemplateElement;

use Ergonode\Designer\Domain\ValueObject\TemplateElement\UiTemplateElementProperty;
use PHPUnit\Framework\TestCase;

class UiTemplateElementTest extends TestCase
{
    public function testUITemplateCreation(): void
    {
        $label = 'Any Element label';

        $element = new UiTemplateElementProperty($label);
        $this->assertEquals($label, $element->getLabel());
        $this->assertEquals(UiTemplateElementProperty::VARIANT, $element->getVariant());
    }
}
