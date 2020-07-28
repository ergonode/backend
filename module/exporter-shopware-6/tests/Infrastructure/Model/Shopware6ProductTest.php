<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use PHPUnit\Framework\TestCase;

/**
 */
class Shopware6ProductTest extends TestCase
{
    /**
     * @var string
     */
    private string $id;
    /**
     * @var string
     */
    private string $sku;
    /**
     * @var string
     */
    private string $name;
    /**
     * @var array
     */
    private array $categories;

    /**
     */
    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->sku = 'any_sku';
        $this->name = 'any_name';
        $this->categories = [
            [
                'id' => 'any_category_id',
            ],
        ];
    }

    /**
     */
    public function testCreateModel()
    {
        $model = new Shopware6Product($this->id, $this->sku, $this->name, null, $this->categories);

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->sku, $model->getSku());
        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->categories, $model->getCategories());
        self::assertNotTrue($model->isModified());
    }

    /**
     */
    public function testSetModel()
    {
        $model = new Shopware6Product();
        $model->setSku($this->sku);
        $model->setName($this->name);
        $model->addCategoryId('any_category_id');

        self::assertEquals($this->sku, $model->getSku());
        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->categories, $model->getCategories());
        self::assertTrue($model->isModified());
    }
}
