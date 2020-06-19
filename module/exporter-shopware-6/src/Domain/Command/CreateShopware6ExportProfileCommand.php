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
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateShopware6ExportProfileCommand implements DomainCommandInterface
{
    /**
     * @var  ExportProfileId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId")
     */
    protected ExportProfileId $id;

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
        $this->categoryTree = $categoryTree;
        $this->attributes = $attributes;
    }

    /**
     * @return ExportProfileId
     */
    public function getId(): ExportProfileId
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
}
