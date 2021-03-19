<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class Shopware6Channel extends AbstractChannel
{
    public const TYPE = 'shopware-6-api';

    private string $host;

    private string $clientId;

    private string $clientKey;

    private ?SegmentId $segment;

    private Language $defaultLanguage;

    /**
     * @var Language[]
     */
    private array $languages;

    private AttributeId $attributeProductName;

    private AttributeId $attributeProductActive;

    private AttributeId $attributeProductStock;

    private AttributeId $attributeProductPriceGross;

    private AttributeId $attributeProductPriceNet;

    private AttributeId $attributeProductTax;

    private ?AttributeId $attributeProductDescription;

    private ?AttributeId $attributeProductGallery;

    private ?AttributeId $attributeProductMetaTitle;

    private ?AttributeId $attributeProductMetaDescription;

    private ?AttributeId $attributeProductKeywords;

    private ?CategoryTreeId $categoryTree;

    /**
     * @var AttributeId[]
     */
    private array $propertyGroup;

    /**
     * @var AttributeId[]
     */
    private array $customField;

    /**
     * @var ProductCollectionId[]
     */
    private array $crossSelling;

    /**
     * @param Language[]                  $languages
     * @param array|AttributeId[]         $propertyGroup
     * @param array|AttributeId[]         $customField
     * @param array|ProductCollectionId[] $crossSelling
     */
    public function __construct(
        ChannelId $id,
        string $name,
        string $host,
        string $clientId,
        string $clientKey,
        ?SegmentId $segment,
        Language $defaultLanguage,
        array $languages,
        AttributeId $attributeProductName,
        AttributeId $attributeProductActive,
        AttributeId $attributeProductStock,
        AttributeId $attributeProductPriceGross,
        AttributeId $attributeProductPriceNet,
        AttributeId $attributeProductTax,
        ?AttributeId $attributeProductDescription,
        ?AttributeId $attributeProductGallery,
        ?AttributeId $attributeProductMetaTitle,
        ?AttributeId $attributeProductMetaDescription,
        ?AttributeId $attributeProductKeywords,
        ?CategoryTreeId $categoryTree,
        array $propertyGroup,
        array $customField,
        array $crossSelling
    ) {
        parent::__construct($id, $name);

        $this->host = $host;
        $this->clientId = $clientId;
        $this->clientKey = $clientKey;
        $this->segment = $segment;
        $this->defaultLanguage = $defaultLanguage;
        $this->languages = $languages;
        $this->attributeProductName = $attributeProductName;
        $this->attributeProductActive = $attributeProductActive;
        $this->attributeProductStock = $attributeProductStock;
        $this->attributeProductPriceGross = $attributeProductPriceGross;
        $this->attributeProductPriceNet = $attributeProductPriceNet;
        $this->attributeProductTax = $attributeProductTax;
        $this->attributeProductDescription = $attributeProductDescription;
        $this->attributeProductGallery = $attributeProductGallery;
        $this->attributeProductMetaTitle = $attributeProductMetaTitle;
        $this->attributeProductMetaDescription = $attributeProductMetaDescription;
        $this->attributeProductKeywords = $attributeProductKeywords;
        $this->categoryTree = $categoryTree;
        $this->propertyGroup = $propertyGroup;
        $this->customField = $customField;
        $this->crossSelling = $crossSelling;
    }

    public static function getType(): string
    {
        return self::TYPE;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    public function getSegment(): ?SegmentId
    {
        return $this->segment;
    }

    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }

    /**
     * @return array|Language[]
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    public function getAttributeProductName(): AttributeId
    {
        return $this->attributeProductName;
    }

    public function getAttributeProductActive(): AttributeId
    {
        return $this->attributeProductActive;
    }

    public function getAttributeProductStock(): AttributeId
    {
        return $this->attributeProductStock;
    }

    public function getAttributeProductPriceGross(): AttributeId
    {
        return $this->attributeProductPriceGross;
    }

    public function getAttributeProductPriceNet(): AttributeId
    {
        return $this->attributeProductPriceNet;
    }

    public function getAttributeProductTax(): AttributeId
    {
        return $this->attributeProductTax;
    }

    public function getAttributeProductDescription(): ?AttributeId
    {
        return $this->attributeProductDescription;
    }

    public function getAttributeProductGallery(): ?AttributeId
    {
        return $this->attributeProductGallery;
    }

    public function getAttributeProductMetaTitle(): ?AttributeId
    {
        return $this->attributeProductMetaTitle;
    }

    public function getAttributeProductMetaDescription(): ?AttributeId
    {
        return $this->attributeProductMetaDescription;
    }

    public function getAttributeProductKeywords(): ?AttributeId
    {
        return $this->attributeProductKeywords;
    }

    public function getCategoryTree(): ?CategoryTreeId
    {
        return $this->categoryTree;
    }

    /**
     * @return AttributeId[]
     */
    public function getPropertyGroup(): array
    {
        return $this->propertyGroup;
    }

    /**
     * @return AttributeId[]
     */
    public function getCustomField(): array
    {
        return $this->customField;
    }

    /**
     * @return ProductCollectionId[]
     */
    public function getCrossSelling(): array
    {
        return $this->crossSelling;
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function setClientKey(string $clientKey): void
    {
        $this->clientKey = $clientKey;
    }

    public function setSegment(?SegmentId $segment): void
    {
        $this->segment = $segment;
    }

    public function setDefaultLanguage(Language $defaultLanguage): void
    {
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * @param Language[] $languages
     */
    public function setLanguages(array $languages): void
    {
        $this->languages = $languages;
    }

    public function setAttributeProductName(AttributeId $attributeProductName): void
    {
        $this->attributeProductName = $attributeProductName;
    }

    public function setAttributeProductActive(AttributeId $attributeProductActive): void
    {
        $this->attributeProductActive = $attributeProductActive;
    }

    public function setAttributeProductStock(AttributeId $attributeProductStock): void
    {
        $this->attributeProductStock = $attributeProductStock;
    }

    public function setAttributeProductPriceGross(AttributeId $attributeProductPriceGross): void
    {
        $this->attributeProductPriceGross = $attributeProductPriceGross;
    }

    public function setAttributeProductPriceNet(AttributeId $attributeProductPriceNet): void
    {
        $this->attributeProductPriceNet = $attributeProductPriceNet;
    }

    public function setAttributeProductTax(AttributeId $attributeProductTax): void
    {
        $this->attributeProductTax = $attributeProductTax;
    }

    public function setAttributeProductDescription(?AttributeId $attributeProductDescription): void
    {
        $this->attributeProductDescription = $attributeProductDescription;
    }

    public function setAttributeProductGallery(?AttributeId $attributeProductGallery): void
    {
        $this->attributeProductGallery = $attributeProductGallery;
    }

    public function setAttributeProductMetaTitle(?AttributeId $attributeProductMetaTitle): void
    {
        $this->attributeProductMetaTitle = $attributeProductMetaTitle;
    }

    public function setAttributeProductMetaDescription(?AttributeId $attributeProductMetaDescription): void
    {
        $this->attributeProductMetaDescription = $attributeProductMetaDescription;
    }

    public function setAttributeProductKeywords(?AttributeId $attributeProductKeywords): void
    {
        $this->attributeProductKeywords = $attributeProductKeywords;
    }

    public function setCategoryTree(?CategoryTreeId $categoryTree): void
    {
        $this->categoryTree = $categoryTree;
    }

    /**
     * @param AttributeId[] $propertyGroup
     */
    public function setPropertyGroup(array $propertyGroup): void
    {
        $this->propertyGroup = $propertyGroup;
    }

    /**
     * @param AttributeId[] $customField
     */
    public function setCustomField(array $customField): void
    {
        $this->customField = $customField;
    }

    /**
     * @param ProductCollectionId[] $crossSelling
     */
    public function setCrossSelling(array $crossSelling): void
    {
        $this->crossSelling = $crossSelling;
    }
}
