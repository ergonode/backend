<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Application\Model;

use Ergonode\Core\Infrastructure\Validator\Constraint as CoreAssert;
use Ergonode\ExporterShopware6\Application\Model\Type\CustomFieldAttributeModel;
use Ergonode\ExporterShopware6\Application\Model\Type\PropertyGroupAttributeModel;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class Shopware6ChannelFormModel
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
     * @var string |null
     */
    public $segment = null;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(),
     *
     * @CoreAssert\LanguageCodeExists()
     * @CoreAssert\LanguageCodeActive()
     *
     */
    public ?string $defaultLanguage = null;

    /**
     * @var array|null
     *
     * @Assert\All({
     *
     *     @CoreAssert\LanguageCodeExists(),
     *     @CoreAssert\LanguageCodeActive()
     * })
     */
    public ?array $languages = [];

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
    public ?AttributeId $attributeProductPriceGross = null;

    /**
     * @var AttributeId|null
     *
     * @Assert\NotNull()
     */
    public ?AttributeId $attributeProductPriceNet = null;

    /**
     * @var AttributeId|null
     *
     * @Assert\NotNull()
     */
    public ?AttributeId $attributeProductTax = null;

    /**
     * @var AttributeId|null
     */
    public ?AttributeId $attributeProductDescription = null;

    /**
     * @var AttributeId|null
     */
    public ?AttributeId $attributeProductGallery = null;

    /**
     * @var string |null
     */
    public ?string $categoryTree = null;

    /**
     * @var PropertyGroupAttributeModel[]
     *
     * @Assert\Valid()
     */
    public array $propertyGroup = [];

    /**
     * @var PropertyGroupAttributeModel[]
     *
     * @Assert\Valid()
     */
    public array $customField = [];

    /**
     * @param Shopware6Channel|null $channel
     */
    public function __construct(Shopware6Channel $channel = null)
    {
        if ($channel) {
            $this->name = $channel->getName();
            $this->host = $channel->getHost();
            $this->clientId = $channel->getClientId();
            $this->clientKey = $channel->getClientKey();
            $this->segment = $channel->getSegment() ? $channel->getSegment()->getValue() : null;
            $this->defaultLanguage = $channel->getDefaultLanguage()->getCode();
            $this->languages = $channel->getLanguages();
            $this->attributeProductName = $channel->getAttributeProductName();
            $this->attributeProductActive = $channel->getAttributeProductActive();
            $this->attributeProductStock = $channel->getAttributeProductStock();
            $this->attributeProductPriceGross = $channel->getAttributeProductPriceGross();
            $this->attributeProductPriceNet = $channel->getAttributeProductPriceNet();
            $this->attributeProductTax = $channel->getAttributeProductTax();
            $this->attributeProductDescription = $channel->getAttributeProductDescription();
            $this->attributeProductGallery = $channel->getAttributeProductGallery();
            $this->categoryTree = $channel->getCategoryTree() ? $channel->getCategoryTree()->getValue() : null;

            foreach ($channel->getPropertyGroup() as $attributeId) {
                $this->propertyGroup[] = new PropertyGroupAttributeModel($attributeId->getValue());
            }

            foreach ($channel->getCustomField() as $attributeId) {
                $this->customField[] = new CustomFieldAttributeModel($attributeId->getValue());
            }
        }
    }
}
