<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Application\Form\Model;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class ExporterShopware6ConfigurationModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $name = null;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    public ?string $host = null;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $clientId = null;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $clientKey = null;

    /**
     * @var Language|null
     *
     * @Assert\NotBlank()
     */
    public ?Language $defaultLanguage = null;

    /**
     * @var AttributeId|null
     *
     * @Assert\NotNull()
     */
    public ?AttributeId $attributeProductName = null;

    /**
     * @var AttributeId|null
     *
     * @Assert\NotNull()
     */
    public ?AttributeId $attributeProductActive = null;

    /**
     * @var AttributeId|null
     *
     * @Assert\NotNull()
     */
    public ?AttributeId $attributeProductStock = null;

    /**
     * @var AttributeId|null
     *
     * @Assert\NotNull()
     */
    public ?AttributeId $attributeProductPrice = null;

    /**
     * @var AttributeId|null
     *
     * @Assert\NotNull()
     */
    public ?AttributeId $attributeProductTax = null;

    /**
     * @var CategoryTreeId|null
     */
    public ?CategoryTreeId $categoryTree = null;

    /**
     * @param Shopware6ExportApiProfile|null $exportProfile
     */
    public function __construct(Shopware6ExportApiProfile $exportProfile = null)
    {
        if ($exportProfile) {
            $this->name = $exportProfile->getName();
            $this->host = $exportProfile->getHost();
            $this->clientId = $exportProfile->getClientId();
            $this->clientKey = $exportProfile->getClientKey();
            $this->defaultLanguage = $exportProfile->getDefaultLanguage();
            $this->attributeProductName = $exportProfile->getProductName();
            $this->attributeProductActive = $exportProfile->getProductActive();
            $this->attributeProductStock = $exportProfile->getProductStock();
            $this->attributeProductPrice = $exportProfile->getProductPrice();
            $this->attributeProductTax = $exportProfile->getProductTax();
            $this->categoryTree = $exportProfile->getCategoryTree();
        }
    }
}
