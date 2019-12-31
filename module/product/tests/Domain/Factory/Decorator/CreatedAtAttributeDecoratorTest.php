<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Factory\Decorator;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Factory\Decorator\CreatedAtAttributeDecorator;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreatedAtAttributeDecoratorTest extends TestCase
{
    /**
     */
    public function testDecoratorCreation(): void
    {
        /** @var ProductFactoryInterface| MockObject $factory */
        $factory = $this->createMock(ProductFactoryInterface::class);
        $factory->expects($this->once())->method('create');

        /** @var ProductId | MockObject $productId */
        $productId = $this->createMock(ProductId::class);

        /** @var Sku | MockObject $sku */
        $sku = $this->createMock(Sku::class);

        $categories = [$this->createMock(CategoryCode::class)];
        $attributes = [$this->createMock(ValueInterface::class)];

        $decorator = new CreatedAtAttributeDecorator($factory);

        $decorator->create($productId, $sku, $categories, $attributes);
    }
}
