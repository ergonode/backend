<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Tests\Domain\Entity\Attribute;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\Entity\Attribute\ProductCollectionSystemAttribute;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductCollectionSystemAttributeTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCategoryAttributeCreation(): void
    {
        $label = $this->createMock(TranslatableString::class);
        $hint = $this->createMock(TranslatableString::class);
        $placeholder = $this->createMock(TranslatableString::class);
        $productCollection = new ProductCollectionSystemAttribute($label, $hint, $placeholder);
        self::assertSame($label, $productCollection->getLabel());
        self::assertSame($hint, $productCollection->getHint());
        self::assertSame($placeholder, $productCollection->getPlaceholder());
        self::assertTrue($productCollection->isSystem());
        self::assertFalse($productCollection->isEditable());
        self::assertTrue($productCollection->isDeletable());
        self::assertFalse($productCollection->isMultilingual());
    }
}
