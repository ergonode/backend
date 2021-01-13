<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper\ProductToLongValueException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\ProductSEOMetaTitleMapper;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper\AbstractProductMapperCase;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class ProductSEOMetaTitleMapperTest extends AbstractProductMapperCase
{
    private const META_TITLE = 'TEST_MetaTitle';

    protected function setUp(): void
    {
        parent::setUp();
        $attribute = $this->createMock(TextareaAttribute::class);
        $this->attributeRepository->method('load')
            ->willReturn($attribute);

        $value = $this->createMock(ValueInterface::class);
        $this->product->method('getAttribute')->willReturn($value);
    }

    public function testNoConfiguration(): void
    {
        $this->channel->method('getAttributeProductMetaTitle')
            ->willReturn(null);

        $mapper = new ProductSEOMetaTitleMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $class = $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);

        self::assertEquals(null, $shopware6Product->getMetaTitle());
        self::assertEquals($shopware6Product, $class);
    }

    public function testNoProductAttributeValue(): void
    {
        $attributeId = $this->createMock(AttributeId::class);
        $this->channel->method('getAttributeProductMetaTitle')
            ->willReturn($attributeId);
        $this->product->method('hasAttribute')->willReturn(false);

        $mapper = new ProductSEOMetaTitleMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $class = $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);

        self::assertEquals(null, $shopware6Product->getMetaTitle());
        self::assertEquals($shopware6Product, $class);
    }

    public function testToLongAttributeValue(): void
    {
        $this->expectException(ProductToLongValueException::class);
        $attributeId = $this->createMock(AttributeId::class);
        $this->channel->method('getAttributeProductMetaTitle')
            ->willReturn($attributeId);

        $this->product->method('hasAttribute')->willReturn(true);

        $this->calculator = $this->createMock(AttributeTranslationInheritanceCalculator::class);
        $this->calculator->method('calculate')
            ->willReturn(str_repeat('a', 256));

        $mapper = new ProductSEOMetaTitleMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);
    }

    public function testCorrectMapper(): void
    {
        $attributeId = $this->createMock(AttributeId::class);
        $this->channel->method('getAttributeProductMetaTitle')
            ->willReturn($attributeId);

        $this->product->method('hasAttribute')->willReturn(true);

        $this->calculator = $this->createMock(AttributeTranslationInheritanceCalculator::class);
        $this->calculator->method('calculate')
            ->willReturn(self::META_TITLE);

        $mapper = new ProductSEOMetaTitleMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);

        self::assertEquals(self::META_TITLE, $shopware6Product->getMetaTitle());
    }
}
