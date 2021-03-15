<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Command\UpdateShopware6ChannelCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateShopware6ChannelCommandTest extends TestCase
{
    /**
     * @var ChannelId|MockObject
     */
    private ChannelId $id;

    private string $name;

    private string $host;

    private string $clientId;

    private string $clientKey;

    /**
     * @var SegmentId|MockObject
     */
    private SegmentId $segment;

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
     * @var AttributeId|MockObject
     */
    private AttributeId $productGallery;

    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $productMetaTitle;

    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $productMetaDescription;

    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $productKeywords;

    /**
     * @var CategoryTreeId|MockObject
     */
    private CategoryTreeId $categoryTreeId;

    /**
     * @var ProductCollectionId|MockObject
     */
    private ProductCollectionId $crossSelling;

    protected function setUp(): void
    {
        $this->id = $this->createMock(ChannelId::class);
        $this->name = 'Any Name';
        $this->host = 'http://example';
        $this->clientId = 'Any Client ID';
        $this->clientKey = 'Any Client KEY';
        $this->segment = $this->createMock(SegmentId::class);
        $this->defaultLanguage = $this->createMock(Language::class);
        $this->languages = [$this->createMock(Language::class)];
        $this->productName = $this->createMock(AttributeId::class);
        $this->productActive = $this->createMock(AttributeId::class);
        $this->productStock = $this->createMock(AttributeId::class);
        $this->productPriceGross = $this->createMock(AttributeId::class);
        $this->productPriceNet = $this->createMock(AttributeId::class);
        $this->productTax = $this->createMock(AttributeId::class);
        $this->productDescription = $this->createMock(AttributeId::class);
        $this->productGallery = $this->createMock(AttributeId::class);
        $this->productMetaTitle = $this->createMock(AttributeId::class);
        $this->productMetaDescription = $this->createMock(AttributeId::class);
        $this->productKeywords = $this->createMock(AttributeId::class);
        $this->categoryTreeId = $this->createMock(CategoryTreeId::class);
        $this->crossSelling = $this->createMock(ProductCollectionId::class);
    }

    public function testCreateCommand(): void
    {
        $command = new UpdateShopware6ChannelCommand(
            $this->id,
            $this->name,
            $this->host,
            $this->clientId,
            $this->clientKey,
            $this->segment,
            $this->defaultLanguage,
            $this->languages,
            $this->productName,
            $this->productActive,
            $this->productStock,
            $this->productPriceGross,
            $this->productPriceNet,
            $this->productTax,
            $this->productDescription,
            $this->productGallery,
            $this->productMetaTitle,
            $this->productMetaDescription,
            $this->productKeywords,
            $this->categoryTreeId,
            [],
            [],
            [$this->crossSelling]
        );

        self::assertEquals($this->id, $command->getId());
        self::assertEquals($this->name, $command->getName());
        self::assertEquals($this->host, $command->getHost());
        self::assertEquals($this->clientId, $command->getClientId());
        self::assertEquals($this->clientKey, $command->getClientKey());
        self::assertEquals($this->segment, $command->getSegment());
        self::assertEquals($this->defaultLanguage, $command->getDefaultLanguage());
        self::assertEquals($this->languages, $command->getLanguages());
        self::assertEquals($this->productName, $command->getProductName());
        self::assertEquals($this->productActive, $command->getProductActive());
        self::assertEquals($this->productStock, $command->getProductStock());
        self::assertEquals($this->productPriceGross, $command->getProductPriceGross());
        self::assertEquals($this->productPriceNet, $command->getProductPriceNet());
        self::assertEquals($this->productTax, $command->getProductTax());
        self::assertEquals($this->productDescription, $command->getProductDescription());
        self::assertEquals($this->productGallery, $command->getProductGallery());
        self::assertEquals($this->productMetaTitle, $command->getProductMetaTitle());
        self::assertEquals($this->productMetaDescription, $command->getProductMetaDescription());
        self::assertEquals($this->productKeywords, $command->getProductKeywords());
        self::assertEquals($this->categoryTreeId, $command->getCategoryTree());
        self::assertIsArray($command->getPropertyGroup());
        self::assertIsArray($command->getCustomField());
        self::assertIsArray($command->getCrossSelling());
        self::assertContains($this->crossSelling, $command->getCrossSelling());
    }
}
