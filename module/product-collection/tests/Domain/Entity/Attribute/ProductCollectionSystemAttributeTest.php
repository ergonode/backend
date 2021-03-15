<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Tests\Domain\Entity\Attribute;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ProductCollection\Domain\Entity\Attribute\ProductCollectionSystemAttribute;
use PHPUnit\Framework\TestCase;

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
        $this->assertSame($label, $productCollection->getLabel());
        $this->assertSame($hint, $productCollection->getHint());
        $this->assertSame($placeholder, $productCollection->getPlaceholder());
        $this->assertTrue($productCollection->isSystem());
        $this->assertFalse($productCollection->isEditable());
        $this->assertTrue($productCollection->isDeletable());
        $this->assertFalse($productCollection->isMultilingual());
    }
}
