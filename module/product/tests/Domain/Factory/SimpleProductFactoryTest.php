<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductSimple\Tests\Domain\Factory;

use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Factory\SimpleProductFactory;
use Ergonode\Product\Domain\ValueObject\ProductType;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class SimpleProductFactoryTest extends TestCase
{
    /**
     */
    public function testFactoryCreation(): void
    {
        /** @var ProductId | MockObject $productId */
        $productId = $this->createMock(ProductId::class);

        /** @var Sku | MockObject $sku */
        $sku = $this->createMock(Sku::class);

        /** @var ProductType | MockObject $type */
        $type = $this->createMock(ProductType::class);

        $categories = [$this->createMock(CategoryId::class)];
        $attributes = [$this->createMock(ValueInterface::class)];

        $factory = new SimpleProductFactory();
        $product = $factory->create($productId, $sku, $type, $categories, $attributes);
        $this->assertInstanceOf(SimpleProduct::class, $product);
        $this->assertSame($productId, $product->getId());
        $this->assertSame($sku, $product->getSku());
        $this->assertSame($type, $product->getType());
        $this->assertSame($categories, $product->getCategories());
        $this->assertSame($attributes, $product->getAttributes());
    }

    /**
     */
    public function testIsSupported(): void
    {
        $factory = new SimpleProductFactory();
        $this->assertTrue($factory->isSupportedBy(SimpleProduct::TYPE));
        $this->assertFalse($factory->isSupportedBy('incorrect type'));
    }
}
