<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\ProductStockMapper;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper\AbstractProductMapperCase;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class ProductStockMapperTest extends AbstractProductMapperCase
{
    private const STOCK = 10;

    protected function setUp(): void
    {
        parent::setUp();

        $attribute = $this->createMock(NumericAttribute::class);
        $this->attributeRepository->method('load')
            ->willReturn($attribute);

        $attributeId = $this->createMock(AttributeId::class);
        $this->channel->method('getAttributeProductStock')
            ->willReturn($attributeId);
    }

    public function testNoProductAttributeValue(): void
    {
        $this->product->method('hasAttribute')->willReturn(false);

        $mapper = new ProductStockMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);

        self::assertEquals(0, $shopware6Product->getStock());
    }

    public function testCorrectMapper(): void
    {
        $this->product->method('hasAttribute')->willReturn(true);

        $this->calculator = $this->createMock(AttributeTranslationInheritanceCalculator::class);
        $this->calculator->method('calculate')
            ->willReturn(self::STOCK);

        $mapper = new ProductStockMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);

        self::assertEquals(self::STOCK, $shopware6Product->getStock());
    }
}
