<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Entity;

use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class VariableProductTest extends TestCase
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
     * @var TemplateId|MockObject
     */
    private TemplateId $templateId;

    protected function setUp(): void
    {
        $this->id = $this->createMock(ProductId::class);
        $this->sku = $this->createMock(Sku::class);
        $this->templateId = $this->createMock(TemplateId::class);
    }

    /**
     * @throws \Exception
     */
    public function testBindingManipulation(): void
    {
        $attribute1 = $this->createMock(SelectAttribute::class);
        $attribute1->method('getId')->willReturn(AttributeId::generate());
        $attribute2 = $this->createMock(SelectAttribute::class);
        $attribute2->method('getId')->willReturn(AttributeId::generate());

        $product = new VariableProduct($this->id, $this->sku, $this->templateId);
        $this->assertFalse($product->hasBind($attribute1->getId()));
        $this->assertFalse($product->hasBind($attribute2->getId()));
        $product->addBind($attribute1);
        $this->assertTrue($product->hasBind($attribute1->getId()));
        $this->assertFalse($product->hasBind($attribute2->getId()));
        $product->addBind($attribute2);
        $this->assertTrue($product->hasBind($attribute1->getId()));
        $this->assertTrue($product->hasBind($attribute2->getId()));
        $this->assertEquals([$attribute1->getId(), $attribute2->getId()], $product->getBindings());
        $product->removeBind($attribute1->getId());
        $this->assertFalse($product->hasBind($attribute1->getId()));
        $this->assertTrue($product->hasBind($attribute2->getId()));
        $product->removeBind($attribute2->getId());
        $this->assertFalse($product->hasBind($attribute1->getId()));
        $this->assertFalse($product->hasBind($attribute2->getId()));
        $this->assertEquals([], $product->getBindings());
    }
}
