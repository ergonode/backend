<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Application\Model;

use Ergonode\Core\Application\Validator as CoreAssert;
use Ergonode\ExporterShopware6\Application\Model\Type\CustomFieldAttributeModel;
use Ergonode\ExporterShopware6\Application\Model\Type\PropertyGroupAttributeModel;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Symfony\Component\Validator\Constraints as Assert;

class Shopware6ChannelFormModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $name = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    public ?string $host = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $clientId = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $clientKey = null;

    public ?string $segment = null;

    /**
     * @Assert\NotBlank(),
     *
     * @CoreAssert\LanguageCodeExists()
     * @CoreAssert\LanguageCodeActive()
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
     * @Assert\NotNull()
     */
    public ?string $attributeProductName = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $attributeProductActive = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $attributeProductStock = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $attributeProductPriceGross = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $attributeProductPriceNet = null;

    /**
     * @Assert\NotNull()
     */
    public ?string $attributeProductTax = null;

    public ?string $attributeProductDescription = null;

    public ?string $attributeProductGallery = null;

    public ?string $attributeProductMetaTitle = null;

    public ?string $attributeProductMetaDescription = null;

    public ?string $attributeProductKeywords = null;

    public ?string $categoryTree = null;

    /**
     * @var PropertyGroupAttributeModel[]
     *
     * @Assert\Valid()
     */
    public array $propertyGroup = [];

    /**
     * @var CustomFieldAttributeModel[]
     *
     * @Assert\Valid()
     */
    public array $customField = [];

    /**
     * @var array
     */
    public array $crossSelling = [];

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
            $this->attributeProductName = $channel->getAttributeProductName()->getValue();
            $this->attributeProductActive = $channel->getAttributeProductActive()->getValue();
            $this->attributeProductStock = $channel->getAttributeProductStock()->getValue();
            $this->attributeProductPriceGross = $channel->getAttributeProductPriceGross()->getValue();
            $this->attributeProductPriceNet = $channel->getAttributeProductPriceNet()->getValue();
            $this->attributeProductTax = $channel->getAttributeProductTax()->getValue();
            $this->attributeProductDescription = $channel->getAttributeProductDescription()
                ? $channel->getAttributeProductDescription()->getValue() : null;
            $this->attributeProductGallery = $channel->getAttributeProductGallery()
                ? $channel->getAttributeProductGallery()->getValue() : null;
            $this->attributeProductMetaTitle = $channel->getAttributeProductMetaTitle()
                ? $channel->getAttributeProductMetaTitle()->getValue() : null;
            $this->attributeProductMetaDescription = $channel->getAttributeProductMetaDescription()
                ? $channel->getAttributeProductMetaDescription()->getValue() : null;
            $this->attributeProductKeywords = $channel->getAttributeProductKeywords()
                ? $channel->getAttributeProductKeywords()->getValue() : null;
            $this->categoryTree = $channel->getCategoryTree() ? $channel->getCategoryTree()->getValue() : null;
            $this->crossSelling = $channel->getCrossSelling();

            foreach ($channel->getPropertyGroup() as $string) {
                $this->propertyGroup[] = new PropertyGroupAttributeModel($string->getValue());
            }

            foreach ($channel->getCustomField() as $string) {
                $this->customField[] = new CustomFieldAttributeModel($string->getValue());
            }
        }
    }
}
