<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Factory\Decorator;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Product\Domain\Entity\Attribute\CreatedAtSystemAttribute;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Factory\Decorator\CreateAtAttributeProductFactoryDecorator;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreatedAtAttributeProductFactoryDecoratorTest extends TestCase
{
    /**
     */
    public function testCreateMethod(): void
    {

        /** @var ProductFactoryInterface| MockObject $factory */
        $factory = $this->createMock(ProductFactoryInterface::class);
        $factory->expects($this->once())
            ->method('create')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->anything(),
                $this->arrayHasKey(CreatedAtSystemAttribute::CODE)
            );

        /** @var ProductId | MockObject $productId */
        $productId = $this->createMock(ProductId::class);

        /** @var Sku | MockObject $sku */
        $sku = $this->createMock(Sku::class);

        $categories = [$this->createMock(CategoryCode::class)];

        $decorator = new CreateAtAttributeProductFactoryDecorator($factory);

        $decorator->create($productId, $sku, $categories);
    }
}
