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
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

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
     * @var TemplateId
     */
    private TemplateId $templateId;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ProductId::class);
        $this->sku = $this->createMock(Sku::class);
        $this->templateId = $this->createMock(TemplateId::class);
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
        self::assertFalse($product->hasChild($child1->getId()));
        self::assertFalse($product->hasChild($child2->getId()));
        $product->addChild($child1);
        self::assertTrue($product->hasChild($child1->getId()));
        self::assertFalse($product->hasChild($child2->getId()));
        $product->addChild($child2);
        self::assertTrue($product->hasChild($child1->getId()));
        self::assertTrue($product->hasChild($child2->getId()));
        self::assertEquals([$child1->getId(), $child2->getId()], $product->getChildren());
        $product->removeChild($child1->getId());
        self::assertFalse($product->hasChild($child1->getId()));
        self::assertTrue($product->hasChild($child2->getId()));
        $product->removeChild($child2->getId());
        self::assertFalse($product->hasChild($child1->getId()));
        self::assertFalse($product->hasChild($child2->getId()));
        self::assertEquals([], $product->getChildren());
    }


    /**
     * @return AbstractAssociatedProduct
     */
    private function getClass(): AbstractAssociatedProduct
    {
        return new class($this->id, $this->sku, $this->templateId) extends AbstractAssociatedProduct {
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
