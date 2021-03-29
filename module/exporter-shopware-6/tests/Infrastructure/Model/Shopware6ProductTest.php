<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductCategory;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductPrice;
use PHPUnit\Framework\TestCase;

class Shopware6ProductTest extends TestCase
{
    private string $id;

    private string $sku;

    private string $name;

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

    private bool $active;

    private int $stock;

    private string $taxId;

    /**
     * @var Shopware6ProductPrice[]
     */
    private array $price;

    private string $parentId;

    /**
     * @var array
     */
    private array $options;

    /**
     * @var array
     */
    private array $media;

    private string $coverId;

    private string $metaTitle;

    private string $metaDescription;

    private string $keywords;

    private string $json;

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
        $price = $this->createMock(Shopware6ProductPrice::class);
        $price->method('jsonSerialize')->willReturn(
            [
                'currency_id' => 'any_currency_id',
                'net' => 1.0,
                'gross' => 1.23,
                'linked' => false,
            ]
        );
        $this->price = [$price];
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

        $this->metaTitle = 'any_meta_title';

        $this->metaDescription = 'any_meta_description';

        $this->keywords = 'any_keywords';

        $this->json = '{"productNumber":"any_sku","name":"any_name","description":"any_description",'
            .'"properties":[{"id":"property_1"},{"id":"property_2"}],"active":true,"stock":10,'
            .'"taxId":"any_tax_id","price":[{"currency_id":"any_currency_id","net":1,"gross":1.23,"linked":false}],'
            .'"parentId":"any_parent_id","options":[{"id":"property_1"},{"id":"property_2"}],'
            .'"coverId":"any_product_media_id","metaTitle":"any_meta_title","metaDescription":"any_meta_description",'
            .'"keywords":"any_keywords"}';
    }

    public function testCreateModel(): void
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
            $this->metaTitle,
            $this->metaDescription,
            $this->keywords
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
        self::assertEquals($this->metaTitle, $model->getMetaTitle());
        self::assertEquals($this->metaDescription, $model->getMetaDescription());
        self::assertEquals($this->keywords, $model->getKeywords());

        self::assertEquals($this->media, $model->getMedia());

        self::assertFalse($model->isNew());
        self::assertFalse($model->isModified());
    }

    public function testSetModel(): void
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
        $model->setMetaTitle($this->metaTitle);
        $model->setMetaDescription($this->metaDescription);
        $model->setKeywords($this->keywords);

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
        self::assertEquals($this->metaTitle, $model->getMetaTitle());
        self::assertEquals($this->metaDescription, $model->getMetaDescription());
        self::assertEquals($this->keywords, $model->getKeywords());

        self::assertTrue($model->isNew());
        self::assertTrue($model->isModified());
    }

    public function testJSON(): void
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
            $this->metaTitle,
            $this->metaDescription,
            $this->keywords
        );

        self::assertEquals($this->json, json_encode($model->jsonSerialize(), JSON_THROW_ON_ERROR));
    }
}
