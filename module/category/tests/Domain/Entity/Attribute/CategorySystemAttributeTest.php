<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Domain\Entity\Attribute;

use Ergonode\Category\Domain\Entity\Attribute\CategorySystemAttribute;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

class CategorySystemAttributeTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCategoryAttributeCreation(): void
    {
        $label = $this->createMock(TranslatableString::class);
        $hint = $this->createMock(TranslatableString::class);
        $placeholder = $this->createMock(TranslatableString::class);
        $attribute = new CategorySystemAttribute($label, $hint, $placeholder);
        $this->assertSame($label, $attribute->getLabel());
        $this->assertSame($hint, $attribute->getHint());
        $this->assertSame($placeholder, $attribute->getPlaceholder());
        $this->assertTrue($attribute->isSystem());
        $this->assertFalse($attribute->isEditable());
        $this->assertTrue($attribute->isDeletable());
        $this->assertFalse($attribute->isMultilingual());
    }
}
