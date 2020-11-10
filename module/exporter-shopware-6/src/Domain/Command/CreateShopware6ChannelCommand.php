<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Command;

use Ergonode\Channel\Domain\Command\CreateChannelCommandInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;

class CreateShopware6ChannelCommand implements CreateChannelCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ChannelId")
     */
    protected ChannelId $id;

    /**
     * @JMS\Type("string")
     */
    protected string $name;
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
     * @JMS\Type("array<string, Ergonode\Core\Domain\ValueObject\Language>")
     */
    private array $languages;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $productName;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $productActive;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $productStock;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $productPriceGross;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $productPriceNet;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $productTax;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $productDescription;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $productGallery;

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
        AttributeId $productName,
        AttributeId $productActive,
        AttributeId $productStock,
        AttributeId $productPriceGross,
        AttributeId $productPriceNet,
        AttributeId $productTax,
        ?AttributeId $productDescription,
        ?AttributeId $productGallery,
        ?CategoryTreeId $categoryTree,
        array $propertyGroup,
        array $customField
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
        $this->categoryTree = $categoryTree;
        $this->propertyGroup = $propertyGroup;
        $this->customField = $customField;
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
}
