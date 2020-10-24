<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class Shopware6Channel extends AbstractChannel
{
    public const TYPE = 'shopware-6-api';

    /**
     * @JMS\Type("string")
     */
    private string $host;

    /**
     * @JMS\Type("string")
     */
    private string $clientId;

    /**
     * @JMS\Type("string")
     */
    private string $clientKey;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private ?SegmentId $segment;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $defaultLanguage;

    /**
     * @var Language[]
     *
     * @JMS\Type("array<Ergonode\Core\Domain\ValueObject\Language>")
     */
    private array $languages;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeProductName;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeProductActive;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeProductStock;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeProductPriceGross;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeProductPriceNet;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeProductTax;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $attributeProductDescription;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $attributeProductGallery;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId")
     */
    private ?CategoryTreeId $categoryTree;

    /**
     * @var AttributeId[]
     *
     * @JMS\Type("array<string, Ergonode\SharedKernel\Domain\Aggregate\AttributeId>")
     */
    private array $propertyGroup;

    /**
     * @var AttributeId[]
     *
     * @JMS\Type("array<string, Ergonode\SharedKernel\Domain\Aggregate\AttributeId>")
     */
    private array $customField;

    /**
     * @param Language[]          $languages
     * @param array|AttributeId[] $propertyGroup
     * @param array|AttributeId[] $customField
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
        ?CategoryTreeId $categoryTree,
        array $propertyGroup,
        array $customField
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
        $this->categoryTree = $categoryTree;
        $this->propertyGroup = $propertyGroup;
        $this->customField = $customField;
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
}
