<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper\Shopware6ExporterProductAttributeException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\ProductNameMapper;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper\AbstractProductMapperCase;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class ProductNameMapperTest extends AbstractProductMapperCase
{
    private const NAME = 'TEST_NAME';

    protected function setUp(): void
    {
        parent::setUp();

        $textAttribute = $this->createMock(TextAttribute::class);
        $this->attributeRepository->method('load')
            ->willReturn($textAttribute);

        $attributeId = $this->createMock(AttributeId::class);
        $this->channel->method('getAttributeProductName')
            ->willReturn($attributeId);
    }

    public function testNoProductAttributeValue(): void
    {
        $this->expectException(Shopware6ExporterProductAttributeException::class);

        $this->product->method('hasAttribute')->willReturn(false);

        $mapper = new ProductNameMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);
    }

    /**
     * @throws Shopware6ExporterProductAttributeException
     */
    public function testCorrectMapper(): void
    {
        $this->product->method('hasAttribute')->willReturn(true);

        $this->calculator = $this->createMock(AttributeTranslationInheritanceCalculator::class);
        $this->calculator->method('calculate')
            ->willReturn(self::NAME);

        $mapper = new ProductNameMapper(
            $this->attributeRepository,
            $this->calculator
        );

        $shopware6Product = new Shopware6Product();
        $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);

        self::assertEquals(self::NAME, $shopware6Product->getName());
    }
}
