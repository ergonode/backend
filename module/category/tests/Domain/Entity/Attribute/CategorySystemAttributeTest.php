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

/**
 */
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
        self::assertSame($label, $attribute->getLabel());
        self::assertSame($hint, $attribute->getHint());
        self::assertSame($placeholder, $attribute->getPlaceholder());
        self::assertTrue($attribute->isSystem());
        self::assertFalse($attribute->isEditable());
        self::assertTrue($attribute->isDeletable());
        self::assertFalse($attribute->isMultilingual());
    }
}
