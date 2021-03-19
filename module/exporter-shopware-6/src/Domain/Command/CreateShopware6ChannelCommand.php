<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Command;

use Ergonode\Channel\Domain\Command\CreateChannelCommandInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

class CreateShopware6ChannelCommand implements CreateChannelCommandInterface
{
    protected ChannelId $id;

    protected string $name;

    private string $host;

    private string $clientId;

    private string $clientKey;

    private ?SegmentId $segment;

    private Language $defaultLanguage;

    /**
     * @var Language[]
     */
    private array $languages;

    private AttributeId $productName;

    private AttributeId $productActive;

    private AttributeId $productStock;

    private AttributeId $productPriceGross;

    private AttributeId $productPriceNet;

    private AttributeId $productTax;

    private ?AttributeId $productDescription;

    private ?AttributeId $productGallery;

    private ?AttributeId $productMetaTitle;

    private ?AttributeId $productMetaDescription;

    private ?AttributeId $productKeywords;

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
        AttributeId $productName,
        AttributeId $productActive,
        AttributeId $productStock,
        AttributeId $productPriceGross,
        AttributeId $productPriceNet,
        AttributeId $productTax,
        ?AttributeId $productDescription,
        ?AttributeId $productGallery,
        ?AttributeId $productMetaTitle,
        ?AttributeId $productMetaDescription,
        ?AttributeId $productKeywords,
        ?CategoryTreeId $categoryTree,
        array $propertyGroup,
        array $customField,
        array $crossSelling
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->host = $host;
        $this->clientId = $clientId;
        $this->clientKey = $clientKey;
        $this->segment = $segment;
        $this->defaultLanguage = $defaultLanguage;
        $this->languages = $languages;
        $this->productName = $productName;
        $this->productActive = $productActive;
        $this->productStock = $productStock;
        $this->productPriceGross = $productPriceGross;
        $this->productPriceNet = $productPriceNet;
        $this->productTax = $productTax;
        $this->productDescription = $productDescription;
        $this->productGallery = $productGallery;
        $this->productMetaTitle = $productMetaTitle;
        $this->productMetaDescription = $productMetaDescription;
        $this->productKeywords = $productKeywords;
        $this->categoryTree = $categoryTree;
        $this->propertyGroup = $propertyGroup;
        $this->customField = $customField;
        $this->crossSelling = $crossSelling;
    }

    public function getId(): ChannelId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
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
     * @return Language[]
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    public function getProductName(): AttributeId
    {
        return $this->productName;
    }

    public function getProductActive(): AttributeId
    {
        return $this->productActive;
    }

    public function getProductStock(): AttributeId
    {
        return $this->productStock;
    }

    public function getProductPriceGross(): AttributeId
    {
        return $this->productPriceGross;
    }

    public function getProductPriceNet(): AttributeId
    {
        return $this->productPriceNet;
    }

    public function getProductTax(): AttributeId
    {
        return $this->productTax;
    }

    public function getProductDescription(): ?AttributeId
    {
        return $this->productDescription;
    }

    public function getProductGallery(): ?AttributeId
    {
        return $this->productGallery;
    }

    public function getProductMetaTitle(): ?AttributeId
    {
        return $this->productMetaTitle;
    }

    public function getProductMetaDescription(): ?AttributeId
    {
        return $this->productMetaDescription;
    }

    public function getProductKeywords(): ?AttributeId
    {
        return $this->productKeywords;
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
}
