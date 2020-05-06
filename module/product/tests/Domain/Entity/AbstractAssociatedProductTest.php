<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\ValueObject\Sku;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;

/**
 */
class AbstractAssociatedProductTest extends TestCase
{
    /**
     * @var ProductId|MockObject
     */
    private ProductId $id;

    /**
     * @var Sku|MockObject
     */
    private Sku $sku;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ProductId::class);
        $this->sku = $this->createMock(Sku::class);
    }

    /**
     * @throws \Exception
     */
    public function testChildManipulation(): void
    {
        $child1 = $this->createMock(AbstractProduct::class);
        $child1->method('getId')->willReturn(ProductId::generate());
        $child2 = $this->createMock(AbstractProduct::class);
        $child2->method('getId')->willReturn(ProductId::generate());

        $product = $this->getClass();
        $this->assertFalse($product->hasChild($child1->getId()));
        $this->assertFalse($product->hasChild($child2->getId()));
        $product->addChild($child1);
        $this->assertTrue($product->hasChild($child1->getId()));
        $this->assertFalse($product->hasChild($child2->getId()));
        $product->addChild($child2);
        $this->assertTrue($product->hasChild($child1->getId()));
        $this->assertTrue($product->hasChild($child2->getId()));
        $this->assertEquals([$child1->getId(), $child2->getId()], $product->getChildren());
        $product->removeChild($child1->getId());
        $this->assertFalse($product->hasChild($child1->getId()));
        $this->assertTrue($product->hasChild($child2->getId()));
        $product->removeChild($child2->getId());
        $this->assertFalse($product->hasChild($child1->getId()));
        $this->assertFalse($product->hasChild($child2->getId()));
        $this->assertEquals([], $product->getChildren());
    }


    /**
     * @return AbstractAssociatedProduct
     */
    private function getClass(): AbstractAssociatedProduct
    {
        return new class($this->id, $this->sku) extends AbstractAssociatedProduct {
            /**
             * @return string
             */
            public function getType(): string
            {
                return 'TYPE';
            }
        };
    }
}
