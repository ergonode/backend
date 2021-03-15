<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class AbstractProductTest extends TestCase
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
     * @var CategoryId|MockObject
     */
    private CategoryId $category;

    /**
     * @var ValueInterface|MockObject
     */
    private ValueInterface $attribute;

    /**
     * @var AttributeCode|MockObject
     */
    private AttributeCode $code;

    private TemplateId $templateId;

    protected function setUp(): void
    {
        $this->id = $this->createMock(ProductId::class);
        $this->sku = $this->createMock(Sku::class);
        $this->category = $this->createMock(CategoryId::class);
        $this->code = $this->createMock(AttributeCode::class);
        $this->code->method('getValue')->willReturn('code');
        $this->attribute = $this->createMock(ValueInterface::class);
        $this->templateId = $this->createMock(TemplateId::class);
    }

    public function testConstruct(): void
    {
        $product = $this->getClass(
            $this->id,
            $this->sku,
            $this->templateId,
            [$this->category],
            [$this->code->getValue() => $this->attribute]
        );
        $this::assertEquals($this->id, $product->getId());
        $this::assertEquals($this->sku, $product->getSku());
        $this::assertEquals([$this->category], $product->getCategories());
        $this::assertEquals([$this->code->getValue() => $this->attribute], $product->getAttributes());
        $this::assertEquals('TYPE', $product->getType());
        $this::assertEquals($this->templateId, $product->getTemplateId());
    }

    public function testConstructWitchBadCategoryObject(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $categories = [$this->createMock(\stdClass::class)];
        $this->getClass($this->id, $this->sku, $this->templateId, $categories, []);
    }

    public function testCategoryManipulation(): void
    {
        $this->category->method('isEqual')->willReturn(true);
        $product = $this->getClass($this->id, $this->sku, $this->templateId, [$this->category], []);
        $this::assertTrue($product->belongToCategory($this->category));
        $product->removeFromCategory($this->category);
        $this::assertFalse($product->belongToCategory($this->category));
        $product->addToCategory($this->category);
        $this::assertEquals([$this->category], $product->getCategories());
    }

    public function testAttributeManipulation(): void
    {
        $newValue = $this->createMock(ValueInterface::class);
        $product = $this->getClass(
            $this->id,
            $this->sku,
            $this->templateId,
            [$this->category],
            [$this->code->getValue() => $this->attribute]
        );
        $this::assertTrue($product->hasAttribute($this->code));
        $this::assertEquals($this->attribute, $product->getAttribute($this->code));
        $product->changeAttribute($this->code, $newValue);
        $this::assertEquals($newValue, $product->getAttribute($this->code));
        $product->removeAttribute($this->code);
        $this::assertFalse($product->hasAttribute($this->code));
        $product->addAttribute($this->code, $this->attribute);
        $this::assertEquals([$this->code->getValue() => $this->attribute], $product->getAttributes());
        $this::assertEquals($this->attribute, $product->getAttribute($this->code));
    }

    public function testRemoveNoteExistsAttribute(): void
    {
        $this->expectException(\RuntimeException::class);
        $product = $this->getClass(
            $this->id,
            $this->sku,
            $this->templateId,
        );
        $product->removeAttribute($this->code);
    }

    public function testAddExistsAttribute(): void
    {
        $this->expectException(\RuntimeException::class);
        $product = $this->getClass(
            $this->id,
            $this->sku,
            $this->templateId,
            [],
            [$this->code->getValue() => $this->attribute]
        );
        $product->addAttribute($this->code, $this->attribute);
    }

    public function testChangeNotExistsAttribute(): void
    {
        $this->expectException(\RuntimeException::class);
        $product = $this->getClass(
            $this->id,
            $this->sku,
            $this->templateId,
            [],
            []
        );
        $product->changeAttribute($this->code, $this->attribute);
    }

    public function testGetNotExistsAttribute(): void
    {
        $this->expectException(\RuntimeException::class);
        $product = $this->getClass(
            $this->id,
            $this->sku,
            $this->templateId,
        );
        $product->getAttribute($this->code);
    }

    /**
     * @throws \Exception
     */
    public function testChangeTemplate(): void
    {
        $templateId = $this->createMock(TemplateId::class);
        $templateId->method('isEqual')->willReturn(false);
        $product = $this->getClass(
            $this->id,
            $this->sku,
            $this->templateId,
        );
        $product->changeTemplate($templateId);
        $this::assertSame($templateId, $product->getTemplateId());
    }

    /**
     * @param array $categories
     * @param array $attributes
     */
    private function getClass(
        ProductId $id,
        Sku $sku,
        TemplateId $templateId,
        array $categories = [],
        array $attributes = []
    ): AbstractProduct {
        return new class($id, $sku, $templateId, $categories, $attributes) extends AbstractProduct {
            public function getType(): string
            {
                return 'TYPE';
            }
        };
    }
}
