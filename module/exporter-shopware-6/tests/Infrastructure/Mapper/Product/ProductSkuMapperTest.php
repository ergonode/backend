<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper\Product;

use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\ProductSkuMapper;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\ExporterShopware6\Tests\Infrastructure\Mapper\AbstractProductMapperCase;
use Ergonode\Product\Domain\ValueObject\Sku;

class ProductSkuMapperTest extends AbstractProductMapperCase
{
    private const SKU = 'TEST_SKU';

    protected function setUp(): void
    {
        parent::setUp();

        $value = $this->createMock(Sku::class);
        $value->method('getValue')->willReturn(self::SKU);
        $this->product->method('getSku')->willReturn($value);
    }

    public function testCorrectMapper(): void
    {
        $mapper = new ProductSkuMapper();

        $shopware6Product = new Shopware6Product();
        $mapper->map($this->channel, $this->export, $shopware6Product, $this->product);

        self::assertEquals(self::SKU, $shopware6Product->getSku());
    }
}
