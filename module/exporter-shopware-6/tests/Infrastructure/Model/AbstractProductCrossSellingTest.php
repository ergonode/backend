<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractProductCrossSelling;
use PHPUnit\Framework\TestCase;

class AbstractProductCrossSellingTest extends TestCase
{
    private string $id;
    private string $name;
    private string $productId;
    private bool $active;
    private string $type;
    private array $assignedProducts;
    private string $json;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->name = 'any_name';
        $this->productId = 'any_product_id';
        $this->active = false;
        $this->type = 'text';
        $this->assignedProducts = [];
        $this->json = '{"name":"any_name","productId":"any_product_id","active":false,"type":"text"}';
    }

    public function testCreateModel(): void
    {
        $model = $this->getClass();

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->productId, $model->getProductId());
        self::assertEquals($this->active, $model->isActive());
        self::assertEquals($this->type, $model->getType());
        self::assertEquals($this->assignedProducts, $model->getAssignedProducts());

        self::assertFalse($model->isModified());
    }

    public function testSetModel(): void
    {
        $model = $this->getClass();

        $name = 'new_name';
        $productId = 'new_product';
        $active = true;
        $type = 'new_type';
        $assignedProducts = [];

        $model->setName($name);
        $model->setProductId($productId);
        $model->setActive($active);
        $model->setType($type);
        $model->setAssignedProducts($assignedProducts);

        self::assertEquals($name, $model->getName());
        self::assertEquals($productId, $model->getProductId());
        self::assertEquals($active, $model->isActive());
        self::assertEquals($type, $model->getType());
        self::assertEquals($assignedProducts, $model->getAssignedProducts());

        self::assertTrue($model->isModified());
    }

    public function testJSON(): void
    {
        $model = $this->getClass();

        self::assertEquals($this->json, json_encode($model->jsonSerialize(), JSON_THROW_ON_ERROR));
    }

    private function getClass(): AbstractProductCrossSelling
    {
        return new class(
            $this->id,
            $this->name,
            $this->productId,
            $this->active,
            $this->type,
            $this->assignedProducts
        ) extends AbstractProductCrossSelling {
        };
    }
}
