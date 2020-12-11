<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model\ProductCrossSelling;

use Ergonode\ExporterShopware6\Infrastructure\Model\ProductCrossSelling\AbstractAssignedProduct;
use PHPUnit\Framework\TestCase;

class AbstractProductCrossSellingAssignedTest extends TestCase
{
    private string $id;

    private string $productId;

    private int $position;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->productId = 'any_product_id';
        $this->position = 5;
    }

    public function testCreateModel(): void
    {
        $model = $this->getClass();

        self::assertEquals($this->productId, $model->getProductId());
        self::assertEquals($this->position, $model->getPosition());

        self::assertFalse($model->isModified());
    }

    public function testSetModel(): void
    {
        $model = $this->getClass();

        $productId = 'new_product';
        $position = 2;

        $model->setProductId($productId);
        $model->setPosition($position);

        self::assertEquals($productId, $model->getProductId());
        self::assertEquals($position, $model->getPosition());

        self::assertTrue($model->isModified());
    }

    private function getClass(): AbstractAssignedProduct
    {
        return new class(
            $this->id,
            $this->productId,
            $this->position
        ) extends AbstractAssignedProduct {
        };
    }
}
