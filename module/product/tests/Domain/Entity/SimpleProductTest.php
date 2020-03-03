<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductSimple\Tests\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class SimpleProductTest extends TestCase
{
    /**
     * @var ProductId|MockObject
     */
    private $id;

    /**
     * @var Sku|MockObject
     */
    private $sku;

    /**
     * @var CategoryCode|MockObject
     */
    private $category;

    /**
     * @var ValueInterface|MockObject
     */
    private $attribute;

    /**
     * @var AttributeCode|MockObject
     */
    private $code;

    /**
     */
    protected function setUp()
    {
        $this->id = $this->createMock(ProductId::class);
        $this->sku = $this->createMock(Sku::class);
        $this->category = $this->createMock(CategoryCode::class);
        $this->code = $this->createMock(AttributeCode::class);
        $this->code->method('getValue')->willReturn('code');
        $this->attribute = $this->createMock(ValueInterface::class);
    }

    /**
     */
    public function testConstruct(): void
    {
        $product = new SimpleProduct(
            $this->id,
            $this->sku,
            [$this->category],
            [$this->code->getValue() => $this->attribute]
        );
        $this->assertEquals($this->id, $product->getId());
        $this->assertEquals($this->sku, $product->getSku());
        $this->assertEquals([$this->category], $product->getCategories());
        $this->assertEquals([$this->code->getValue() => $this->attribute], $product->getAttributes());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructWitchBadCategoryObject(): void
    {
        $categories = [$this->createMock(\stdClass::class)];
        new SimpleProduct($this->id, $this->sku, $categories, []);
    }

    /**
     */
    public function testCategoryManipulation(): void
    {
        $product = new SimpleProduct($this->id, $this->sku, [$this->category], []);
        $this->assertTrue($product->belongToCategory($this->category));
        $product->removeFromCategory($this->category);
        $this->assertFalse($product->belongToCategory($this->category));
        $product->addToCategory($this->category);
        $this->assertEquals([$this->category], $product->getCategories());
    }

    /**
     */
    public function testAttributeManipulation(): void
    {
        $product = new SimpleProduct(
            $this->id,
            $this->sku,
            [$this->category],
            [$this->code->getValue() => $this->attribute]
        );
        $this->assertTrue($product->hasAttribute($this->code));
        $product->removeAttribute($this->code);
        $this->assertFalse($product->hasAttribute($this->code));
        $product->addAttribute($this->code, $this->attribute);
        $this->assertEquals([$this->code->getValue() => $this->attribute], $product->getAttributes());
        $this->assertEquals($this->attribute, $product->getAttribute($this->code));
    }
}
