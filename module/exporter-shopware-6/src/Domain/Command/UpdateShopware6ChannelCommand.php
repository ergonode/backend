<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
class UpdateShopware6ChannelCommand implements DomainCommandInterface
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ChannelId")
     */
    protected ChannelId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $name;
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
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $defaultLanguage;
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $productName;

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $productActive;

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $productStock;

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $productPrice;

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $productTax;

    /**
     * @var AttributeId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $productDescription;

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
     * @param Language            $defaultLanguage
     * @param AttributeId         $productName
     * @param AttributeId         $productActive
     * @param AttributeId         $productStock
     * @param AttributeId         $productPrice
     * @param AttributeId         $productTax
     * @param AttributeId|null    $productDescription
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
        Language $defaultLanguage,
        AttributeId $productName,
        AttributeId $productActive,
        AttributeId $productStock,
        AttributeId $productPrice,
        AttributeId $productTax,
        ?AttributeId $productDescription,
        ?CategoryTreeId $categoryTree,
        array $propertyGroup,
        array $customField
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->host = $host;
        $this->clientId = $clientId;
        $this->clientKey = $clientKey;
        $this->defaultLanguage = $defaultLanguage;
        $this->productName = $productName;
        $this->productActive = $productActive;
        $this->productStock = $productStock;
        $this->productPrice = $productPrice;
        $this->productTax = $productTax;
        $this->productDescription = $productDescription;
        $this->categoryTree = $categoryTree;
        $this->propertyGroup = $propertyGroup;
        $this->customField = $customField;
    }

    /**
     * @return ChannelId
     */
    public function getId(): ChannelId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }

    /**
     * @return AttributeId
     */
    public function getProductName(): AttributeId
    {
        return $this->productName;
    }

    /**
     * @return AttributeId
     */
    public function getProductActive(): AttributeId
    {
        return $this->productActive;
    }

    /**
     * @return AttributeId
     */
    public function getProductStock(): AttributeId
    {
        return $this->productStock;
    }

    /**
     * @return AttributeId
     */
    public function getProductPrice(): AttributeId
    {
        return $this->productPrice;
    }

    /**
     * @return AttributeId
     */
    public function getProductTax(): AttributeId
    {
        return $this->productTax;
    }

    /**
     * @return AttributeId|null
     */
    public function getProductDescription(): ?AttributeId
    {
        return $this->productDescription;
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
}
