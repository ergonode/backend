<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Domain\Entity\Profile\ExportProfileInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Shopware6ExportApiProfile extends AbstractExportProfile implements ExportProfileInterface
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
    private array $attributes;

    /**
     * @param ExportProfileId     $id
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
     * @param CategoryTreeId|null $categoryTree
     * @param array|AttributeId[] $attributes
     */
    public function __construct(
        ExportProfileId $id,
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
        ?CategoryTreeId $categoryTree,
        array $attributes
    ) {
        parent::__construct($id, $name);

        $this->host = $host;
        $this->clientId = $clientId;
        $this->clientKey = $clientKey;
        $this->defaultLanguage = $defaultLanguage;
        $this->productName = $productName;
        $this->productActive = $productActive;
        $this->productStock = $productStock;
        $this->productPrice = $productPrice;
        $this->productTax = $productTax;
        $this->categoryTree = $categoryTree;
        $this->attributes = $attributes;
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
     * @return CategoryTreeId|null
     */
    public function getCategoryTree(): ?CategoryTreeId
    {
        return $this->categoryTree;
    }

    /**
     * @return AttributeId[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * @param Language $defaultLanguage
     */
    public function setDefaultLanguage(Language $defaultLanguage): void
    {
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * @param AttributeId $productName
     */
    public function setProductName(AttributeId $productName): void
    {
        $this->productName = $productName;
    }

    /**
     * @param AttributeId $productActive
     */
    public function setProductActive(AttributeId $productActive): void
    {
        $this->productActive = $productActive;
    }

    /**
     * @param AttributeId $productStock
     */
    public function setProductStock(AttributeId $productStock): void
    {
        $this->productStock = $productStock;
    }

    /**
     * @param AttributeId $productPrice
     */
    public function setProductPrice(AttributeId $productPrice): void
    {
        $this->productPrice = $productPrice;
    }

    /**
     * @param AttributeId $productTax
     */
    public function setProductTax(AttributeId $productTax): void
    {
        $this->productTax = $productTax;
    }

    /**
     * @param CategoryTreeId|null $categoryTree
     */
    public function setCategoryTree(?CategoryTreeId $categoryTree): void
    {
        $this->categoryTree = $categoryTree;
    }

    /**
     * @param AttributeId[] $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }
}
