<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
class Shopware6ChannelTest extends TestCase
{
    /**
     * @var ChannelId|MockObject
     */
    private ChannelId $id;

    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $host;

    /**
     * @var string
     */
    private string $clientId;

    /**
     * @var string
     */
    private string $clientKey;

    /**
     * @var Language|MockObject
     */
    private Language $defaultLanguage;

    /**
     * @var array|MockObject
     */
    private array $languages;

    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $productName;

    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $productActive;

    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $productStock;

    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $productPriceGross;

    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $productPriceNet;

    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $productTax;

    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $productDescription;

    /**
     * @var CategoryTreeId|MockObject
     */
    private CategoryTreeId $categoryTreeId;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ChannelId::class);
        $this->name = 'Any Name';
        $this->host = 'http://example';
        $this->clientId = 'Any Client ID';
        $this->clientKey = 'Any Client KEY';
        $this->defaultLanguage = $this->createMock(Language::class);
        $this->languages = [$this->createMock(Language::class)];
        $this->productName = $this->createMock(AttributeId::class);
        $this->productActive = $this->createMock(AttributeId::class);
        $this->productStock = $this->createMock(AttributeId::class);
        $this->productPriceGross = $this->createMock(AttributeId::class);
        $this->productPriceNet = $this->createMock(AttributeId::class);
        $this->productTax = $this->createMock(AttributeId::class);
        $this->productDescription = $this->createMock(AttributeId::class);
        $this->categoryTreeId = $this->createMock(CategoryTreeId::class);
    }

    /**
     */
    public function testCreateEntity(): void
    {
        $entity = new Shopware6Channel(
            $this->id,
            $this->name,
            $this->host,
            $this->clientId,
            $this->clientKey,
            $this->defaultLanguage,
            $this->languages,
            $this->productName,
            $this->productActive,
            $this->productStock,
            $this->productPriceGross,
            $this->productPriceNet,
            $this->productTax,
            $this->productDescription,
            $this->categoryTreeId,
            [],
            []
        );

        self::assertEquals($this->id, $entity->getId());
        self::assertEquals('shopware-6-api', $entity->getType());
        self::assertEquals($this->name, $entity->getName());
        self::assertEquals($this->host, $entity->getHost());
        self::assertEquals($this->clientId, $entity->getClientId());
        self::assertEquals($this->clientKey, $entity->getClientKey());
        self::assertEquals($this->defaultLanguage, $entity->getDefaultLanguage());
        self::assertEquals($this->languages, $entity->getLanguages());
        self::assertEquals($this->productName, $entity->getAttributeProductName());
        self::assertEquals($this->productActive, $entity->getAttributeProductActive());
        self::assertEquals($this->productStock, $entity->getAttributeProductStock());
        self::assertEquals($this->productPriceGross, $entity->getAttributeProductPriceGross());
        self::assertEquals($this->productPriceNet, $entity->getAttributeProductPriceNet());
        self::assertEquals($this->productTax, $entity->getAttributeProductTax());
        self::assertEquals($this->productDescription, $entity->getAttributeProductDescription());
        self::assertEquals($this->categoryTreeId, $entity->getCategoryTree());
        self::assertIsArray($entity->getPropertyGroup());
        self::assertIsArray($entity->getCustomField());
    }

    /**
     * @throws \Exception
     */
    public function testSetEntity(): void
    {
        $entity = new Shopware6Channel(
            $this->id,
            $this->name,
            $this->host,
            $this->clientId,
            $this->clientKey,
            $this->defaultLanguage,
            $this->languages,
            $this->productName,
            $this->productActive,
            $this->productStock,
            $this->productPriceGross,
            $this->productPriceNet,
            $this->productTax,
            $this->productDescription,
            $this->categoryTreeId,
            [],
            []
        );

        $id = $this->createMock(ChannelId::class);
        $name = 'New Name';
        $host = 'http://example2';
        $clientId = 'New Client ID';
        $clientKey = 'New Client KEY';
        $defaultLanguage = $this->createMock(Language::class);
        $languages = [$this->createMock(Language::class)];
        $productName = $this->createMock(AttributeId::class);
        $productActive = $this->createMock(AttributeId::class);
        $productStock = $this->createMock(AttributeId::class);
        $productPriceGross = $this->createMock(AttributeId::class);
        $productPriceNet = $this->createMock(AttributeId::class);
        $productTax = $this->createMock(AttributeId::class);
        $productDescription = $this->createMock(AttributeId::class);
        $categoryTreeId = $this->createMock(CategoryTreeId::class);


        $entity->setName($name);
        $entity->setHost($host);
        $entity->setClientId($clientId);
        $entity->setClientKey($clientKey);
        $entity->setDefaultLanguage($defaultLanguage);
        $entity->setLanguages($languages);
        $entity->setAttributeProductName($productName);
        $entity->setAttributeProductActive($productActive);
        $entity->setAttributeProductStock($productStock);
        $entity->setAttributeProductPriceGross($productPriceGross);
        $entity->setAttributeProductPriceNet($productPriceNet);
        $entity->setAttributeProductTax($productTax);
        $entity->setAttributeProductDescription($productDescription);
        $entity->setCategoryTree($categoryTreeId);
        $entity->setPropertyGroup([]);
        $entity->setCustomField([]);


        self::assertEquals($id, $entity->getId());
        self::assertEquals($name, $entity->getName());
        self::assertEquals($host, $entity->getHost());
        self::assertEquals($clientId, $entity->getClientId());
        self::assertEquals($clientKey, $entity->getClientKey());
        self::assertEquals($defaultLanguage, $entity->getDefaultLanguage());
        self::assertEquals($languages, $entity->getLanguages());
        self::assertEquals($productName, $entity->getAttributeProductName());
        self::assertEquals($productActive, $entity->getAttributeProductActive());
        self::assertEquals($productStock, $entity->getAttributeProductStock());
        self::assertEquals($productPriceGross, $entity->getAttributeProductPriceGross());
        self::assertEquals($productPriceNet, $entity->getAttributeProductPriceNet());
        self::assertEquals($productTax, $entity->getAttributeProductTax());
        self::assertEquals($productDescription, $entity->getAttributeProductDescription());
        self::assertEquals($categoryTreeId, $entity->getCategoryTree());
        self::assertIsArray($entity->getPropertyGroup());
        self::assertIsArray($entity->getCustomField());
    }
}
