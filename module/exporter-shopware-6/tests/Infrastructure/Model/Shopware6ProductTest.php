<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductCategory;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductPrice;
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
     * @var string
     */
    private string $description;

    /**
     * @var array
     */
    private array $categories;

    /**
     * @var array
     */
    private array $properties;

    /**
     * @var array
     */
    private array $customFields;

    /**
     * @var bool
     */
    private bool $active;

    /**
     * @var int
     */
    private int $stock;

    /**
     * @var string
     */
    private string $taxId;

    /**
     * @var Shopware6ProductPrice[]
     */
    private array $price;

    /**
     * @var string
     */
    private string $parentId;

    /**
     * @var array
     */
    private array $options;

    /**
     * @var array
     */
    private array $media;

    /**
     * @var string
     */
    private string $coverId;


    /**
     */
    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->sku = 'any_sku';
        $this->name = 'any_name';
        $this->description = 'any_description';
        $this->categories = [
            $this->createMock(Shopware6ProductCategory::class),
        ];
        $this->properties = [
            [
                'id' => 'property_1',
            ],
            [
                'id' => 'property_2',
            ],
        ];
        $this->customFields = [];
        $this->active = true;
        $this->stock = 10;
        $this->taxId = 'any_tax_id';
        $this->price = [
            $this->createMock(Shopware6ProductPrice::class),
        ];
        $this->parentId = 'any_parent_id';
        $this->options = [
            [
                'id' => 'property_1',
            ],
            [
                'id' => 'property_2',
            ],
        ];
        $this->media = [];

        $this->coverId = 'any_product_media_id';
    }

    /**
     */
    public function testCreateModel():void
    {
        $model = new Shopware6Product(
            $this->id,
            $this->sku,
            $this->name,
            $this->description,
            $this->properties,
            $this->customFields,
            $this->parentId,
            $this->options,
            $this->active,
            $this->stock,
            $this->taxId,
            $this->price,
            $this->coverId,
        );

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->sku, $model->getSku());
        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->description, $model->getDescription());
        self::assertEquals($this->properties, $model->getProperties());
        self::assertEquals($this->customFields, $model->getCustomFields());
        self::assertEquals($this->parentId, $model->getParentId());
        self::assertEquals($this->options, $model->getOptions());
        self::assertEquals($this->active, $model->isActive());
        self::assertEquals($this->stock, $model->getStock());
        self::assertEquals($this->taxId, $model->getTaxId());
        self::assertEquals($this->price, $model->getPrice());
        self::assertEquals($this->coverId, $model->getCoverId());
        self::assertEquals($this->media, $model->getMedia());

        self::assertFalse($model->isNew());
        self::assertFalse($model->isModified());
    }

    /**
     */
    public function testSetModel():void
    {
        $model = new Shopware6Product();
        $model->setSku($this->sku);
        $model->setName($this->name);
        $model->setDescription($this->description);
        $model->addCategory($this->categories[0]);

        $model->addProperty('property_1');
        $model->addProperty('property_2');

        $model->setParentId($this->parentId);

        $model->addOptions('property_1');
        $model->addOptions('property_2');

        $model->setActive($this->active);
        $model->setStock($this->stock);
        $model->setTaxId($this->taxId);
        $model->addPrice($this->price[0]);
        $model->setCoverId($this->coverId);

        self::assertEquals($this->sku, $model->getSku());
        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->sku, $model->getSku());
        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->description, $model->getDescription());
        self::assertEquals($this->categories, $model->getCategories());
        self::assertEquals($this->properties, $model->getProperties());
        self::assertEquals($this->customFields, $model->getCustomFields());
        self::assertEquals($this->parentId, $model->getParentId());
        self::assertEquals($this->options, $model->getOptions());
        self::assertEquals($this->active, $model->isActive());
        self::assertEquals($this->stock, $model->getStock());
        self::assertEquals($this->taxId, $model->getTaxId());
        self::assertEquals($this->price, $model->getPrice());
        self::assertEquals($this->coverId, $model->getCoverId());
        self::assertEquals($this->media, $model->getMedia());

        self::assertTrue($model->isNew());
        self::assertTrue($model->isModified());
    }
}
