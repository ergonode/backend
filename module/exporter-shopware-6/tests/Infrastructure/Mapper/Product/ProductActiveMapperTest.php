<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\ProductActiveMapper;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper\AbstractProductMapperCase;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class ProductActiveMapperTest extends AbstractProductMapperCase
{
    private const QTY_ACTIVE = 1;
    private const QTY_NOT_ACTIVE = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $textAttribute = $this->createMock(NumericAttribute::class);
        $this->attributeRepository->method('load')
            ->willReturn($textAttribute);

        $attributeId = $this->createMock(AttributeId::class);
        $this->channel->method('getAttributeProductName')
            ->willReturn($attributeId);
    }

    public function testNoAttributeMapper(): void
    {
        $this->product->method('hasAttribute')->willReturn(false);

        $mapper = new ProductActiveMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);

        self::assertEquals(false, $shopware6Product->isActive());
    }

    public function testNotActiveCorrectMapper(): void
    {
        $this->product->method('hasAttribute')->willReturn(true);

        $this->calculator = $this->createMock(AttributeTranslationInheritanceCalculator::class);
        $this->calculator->method('calculate')
            ->willReturn(self::QTY_NOT_ACTIVE);

        $mapper = new ProductActiveMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);

        self::assertEquals(false, $shopware6Product->isActive());
    }

    public function testActiveCorrectMapper(): void
    {
        $this->product->method('hasAttribute')->willReturn(true);

        $this->calculator = $this->createMock(AttributeTranslationInheritanceCalculator::class);
        $this->calculator->method('calculate')
            ->willReturn(self::QTY_ACTIVE);

        $mapper = new ProductActiveMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);

        self::assertEquals(true, $shopware6Product->isActive());
    }
}
