<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
class Shopware6Channel extends AbstractChannel
{
    public const TYPE = 'shopware-6-api';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $host;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $clientId;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $clientKey;

    /**
     * @var SegmentId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private ?SegmentId $segment;

    /**
     * @var Language
     *
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
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeProductName;

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeProductActive;

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeProductStock;

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeProductPriceGross;

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeProductPriceNet;

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeProductTax;

    /**
     * @var AttributeId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $attributeProductDescription;

    /**
     * @var AttributeId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $attributeProductGallery;

    /**
     * @var CategoryTreeId|null
     *
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
     * @param ChannelId           $id
     * @param string              $name
     * @param string              $host
     * @param string              $clientId
     * @param string              $clientKey
     * @param SegmentId|null      $segment
     * @param Language            $defaultLanguage
     * @param Language[]          $languages
     * @param AttributeId         $attributeProductName
     * @param AttributeId         $attributeProductActive
     * @param AttributeId         $attributeProductStock
     * @param AttributeId         $attributeProductPriceGross
     * @param AttributeId         $attributeProductPriceNet
     * @param AttributeId         $attributeProductTax
     * @param AttributeId|null    $attributeProductDescription
     * @param AttributeId|null    $attributeProductGallery
     * @param CategoryTreeId|null $categoryTree
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

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    /**
     * @return SegmentId|null
     */
    public function getSegment(): ?SegmentId
    {
        return $this->segment;
    }

    /**
     * @return Language
     */
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

    /**
     * @return AttributeId
     */
    public function getAttributeProductName(): AttributeId
    {
        return $this->attributeProductName;
    }

    /**
     * @return AttributeId
     */
    public function getAttributeProductActive(): AttributeId
    {
        return $this->attributeProductActive;
    }

    /**
     * @return AttributeId
     */
    public function getAttributeProductStock(): AttributeId
    {
        return $this->attributeProductStock;
    }

    /**
     * @return AttributeId
     */
    public function getAttributeProductPriceGross(): AttributeId
    {
        return $this->attributeProductPriceGross;
    }

    /**
     * @return AttributeId
     */
    public function getAttributeProductPriceNet(): AttributeId
    {
        return $this->attributeProductPriceNet;
    }

    /**
     * @return AttributeId
     */
    public function getAttributeProductTax(): AttributeId
    {
        return $this->attributeProductTax;
    }

    /**
     * @return AttributeId|null
     */
    public function getAttributeProductDescription(): ?AttributeId
    {
        return $this->attributeProductDescription;
    }

    /**
     * @return AttributeId|null
     */
    public function getAttributeProductGallery(): ?AttributeId
    {
        return $this->attributeProductGallery;
    }

    /**
     * @return CategoryTreeId|null
     */
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
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * @param string $clientId
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @param string $clientKey
     */
    public function setClientKey(string $clientKey): void
    {
        $this->clientKey = $clientKey;
    }

    /**
     * @param SegmentId|null $segment
     */
    public function setSegment(?SegmentId $segment): void
    {
        $this->segment = $segment;
    }

    /**
     * @param Language $defaultLanguage
     */
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

    /**
     * @param AttributeId $attributeProductName
     */
    public function setAttributeProductName(AttributeId $attributeProductName): void
    {
        $this->attributeProductName = $attributeProductName;
    }

    /**
     * @param AttributeId $attributeProductActive
     */
    public function setAttributeProductActive(AttributeId $attributeProductActive): void
    {
        $this->attributeProductActive = $attributeProductActive;
    }

    /**
     * @param AttributeId $attributeProductStock
     */
    public function setAttributeProductStock(AttributeId $attributeProductStock): void
    {
        $this->attributeProductStock = $attributeProductStock;
    }

    /**
     * @param AttributeId $attributeProductPriceGross
     */
    public function setAttributeProductPriceGross(AttributeId $attributeProductPriceGross): void
    {
        $this->attributeProductPriceGross = $attributeProductPriceGross;
    }

    /**
     * @param AttributeId $attributeProductPriceNet
     */
    public function setAttributeProductPriceNet(AttributeId $attributeProductPriceNet): void
    {
        $this->attributeProductPriceNet = $attributeProductPriceNet;
    }

    /**
     * @param AttributeId $attributeProductTax
     */
    public function setAttributeProductTax(AttributeId $attributeProductTax): void
    {
        $this->attributeProductTax = $attributeProductTax;
    }

    /**
     * @param AttributeId|null $attributeProductDescription
     */
    public function setAttributeProductDescription(?AttributeId $attributeProductDescription): void
    {
        $this->attributeProductDescription = $attributeProductDescription;
    }

    /**
     * @param AttributeId|null $attributeProductGallery
     */
    public function setAttributeProductGallery(?AttributeId $attributeProductGallery): void
    {
        $this->attributeProductGallery = $attributeProductGallery;
    }

    /**
     * @param CategoryTreeId|null $categoryTree
     */
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
