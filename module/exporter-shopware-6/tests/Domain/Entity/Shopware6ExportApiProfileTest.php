<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class Shopware6ExportApiProfileTest extends TestCase
{
    /**
     * @var ExportProfileId|MockObject
     */
    private ExportProfileId $id;

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
     * @var Language[]|MockObject
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
    private AttributeId $productPrice;

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
        $this->id = $this->createMock(ExportProfileId::class);
        $this->name = 'Any Name';
        $this->host = 'http://example';
        $this->clientId = 'Any Client ID';
        $this->clientKey = 'Any Client KEY';
        $this->defaultLanguage = $this->createMock(Language::class);
        $this->languages = [$this->createMock(Language::class)];
        $this->productName = $this->createMock(AttributeId::class);
        $this->productActive = $this->createMock(AttributeId::class);
        $this->productStock = $this->createMock(AttributeId::class);
        $this->productPrice = $this->createMock(AttributeId::class);
        $this->productTax = $this->createMock(AttributeId::class);
        $this->productDescription = $this->createMock(AttributeId::class);
        $this->categoryTreeId = $this->createMock(CategoryTreeId::class);
    }

    /**
     */
    public function testCreateEntity(): void
    {
        $entity = new Shopware6ExportApiProfile(
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
            $this->productPrice,
            $this->productTax,
            $this->productDescription,
            $this->categoryTreeId,
            [],
            []
        );

        $this->assertEquals($this->id, $entity->getId());
        $this->assertEquals('shopware-6-api', $entity->getType());
        $this->assertEquals($this->name, $entity->getName());
        $this->assertEquals($this->host, $entity->getHost());
        $this->assertEquals($this->clientId, $entity->getClientId());
        $this->assertEquals($this->clientKey, $entity->getClientKey());
        $this->assertEquals($this->defaultLanguage, $entity->getDefaultLanguage());
        $this->assertEquals($this->languages, $entity->getLanguages());
        $this->assertEquals($this->productName, $entity->getProductName());
        $this->assertEquals($this->productActive, $entity->getProductActive());
        $this->assertEquals($this->productStock, $entity->getProductStock());
        $this->assertEquals($this->productPrice, $entity->getProductPrice());
        $this->assertEquals($this->productTax, $entity->getProductTax());
        $this->assertEquals($this->productDescription, $entity->getProductDescription());
        $this->assertEquals($this->categoryTreeId, $entity->getCategoryTree());
        $this->assertIsArray($entity->getPropertyGroup());
        $this->assertIsArray($entity->getCustomField());
    }

    /**
     */
    public function testSetEntity(): void
    {
        $entity = new Shopware6ExportApiProfile(
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
            $this->productPrice,
            $this->productTax,
            $this->productDescription,
            $this->categoryTreeId,
            [],
            []
        );

        $id = $this->createMock(ExportProfileId::class);
        $name = 'New Name';
        $host = 'http://example2';
        $clientId = 'New Client ID';
        $clientKey = 'New Client KEY';
        $defaultLanguage = $this->createMock(Language::class);
        $languages = [$this->createMock(Language::class)];
        $productName = $this->createMock(AttributeId::class);
        $productActive = $this->createMock(AttributeId::class);
        $productStock = $this->createMock(AttributeId::class);
        $productPrice = $this->createMock(AttributeId::class);
        $productTax = $this->createMock(AttributeId::class);
        $productDescription = $this->createMock(AttributeId::class);
        $categoryTreeId = $this->createMock(CategoryTreeId::class);

        $entity->setName($name);
        $entity->setHost($host);
        $entity->setClientId($clientId);
        $entity->setClientKey($clientKey);
        $entity->setDefaultLanguage($defaultLanguage);
        $entity->setLanguages($languages);
        $entity->setProductName($productName);
        $entity->setProductActive($productActive);
        $entity->setProductStock($productStock);
        $entity->setProductPrice($productPrice);
        $entity->setProductTax($productTax);
        $entity->setProductDescription($productDescription);
        $entity->setCategoryTree($categoryTreeId);
        $entity->setPropertyGroup([]);
        $entity->setCustomField([]);

        $this->assertEquals($id, $entity->getId());
        $this->assertEquals($name, $entity->getName());
        $this->assertEquals($host, $entity->getHost());
        $this->assertEquals($clientId, $entity->getClientId());
        $this->assertEquals($clientKey, $entity->getClientKey());
        $this->assertEquals($defaultLanguage, $entity->getDefaultLanguage());
        $this->assertEquals($languages, $entity->getLanguages());
        $this->assertEquals($productName, $entity->getProductName());
        $this->assertEquals($productActive, $entity->getProductActive());
        $this->assertEquals($productStock, $entity->getProductStock());
        $this->assertEquals($productPrice, $entity->getProductPrice());
        $this->assertEquals($productTax, $entity->getProductTax());
        $this->assertEquals($productDescription, $entity->getProductDescription());
        $this->assertEquals($categoryTreeId, $entity->getCategoryTree());
        $this->assertIsArray($entity->getPropertyGroup());
        $this->assertIsArray($entity->getCustomField());
    }
}
