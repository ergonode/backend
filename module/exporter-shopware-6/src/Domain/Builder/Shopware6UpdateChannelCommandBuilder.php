<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Builder;

use Ergonode\Channel\Domain\Command\ChannelCommandInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Symfony\Component\Form\FormInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\Channel\Application\Provider\UpdateChannelCommandBuilderInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\ExporterShopware6\Domain\Command\UpdateShopware6ChannelCommand;
use Ergonode\ExporterShopware6\Application\Model\Shopware6ChannelFormModel;

class Shopware6UpdateChannelCommandBuilder implements UpdateChannelCommandBuilderInterface
{
    public function supported(string $type): bool
    {
        return Shopware6Channel::TYPE === $type;
    }

    public function build(ChannelId $id, FormInterface $form): ChannelCommandInterface
    {
        /** @var Shopware6ChannelFormModel $data */
        $data = $form->getData();

        $name = $data->name;
        $host = $data->host;
        $clientId = $data->clientId;
        $clientKey = $data->clientKey;
        $segment = $data->segment;
        $defaultLanguage = $data->defaultLanguage;
        $languages = $data->languages;
        $attributeProductName = $data->attributeProductName;
        $attributeProductActive = $data->attributeProductActive;
        $attributeProductStock = $data->attributeProductStock;
        $attributeProductPriceGross = $data->attributeProductPriceGross;
        $attributeProductPriceNet = $data->attributeProductPriceNet;
        $attributeProductTax = $data->attributeProductTax;
        $attributeProductDescription = $data->attributeProductDescription;
        $attributeProductGallery = $data->attributeProductGallery;
        $attributeProductMetaTitle = $data->attributeProductMetaTitle;
        $attributeProductMetaDescription = $data->attributeProductMetaDescription;
        $attributeProductKeywords = $data->attributeProductKeywords;
        $categoryTree = $data->categoryTree;
        $crossSelling = $data->crossSelling;

        $propertyGroup = [];
        foreach ($data->propertyGroup as $attribute) {
            $propertyGroup[] = new AttributeId($attribute->id);
        }

        $customField = [];
        foreach ($data->customField as $attribute) {
            $customField[] = new AttributeId($attribute->id);
        }

        $languageObjects = [];
        foreach ($languages as $language) {
            $languageObjects[] = new Language($language);
        }
        $crossSellingObjects = [];
        foreach ($crossSelling as $crossSell) {
            $crossSellingObjects[] = new ProductCollectionId($crossSell);
        }

        return new UpdateShopware6ChannelCommand(
            $id,
            $name,
            $host,
            $clientId,
            $clientKey,
            $segment ? new SegmentId($segment) : null,
            new Language($defaultLanguage),
            $languageObjects,
            new AttributeId($attributeProductName),
            new AttributeId($attributeProductActive),
            new AttributeId($attributeProductStock),
            new AttributeId($attributeProductPriceGross),
            new AttributeId($attributeProductPriceNet),
            new AttributeId($attributeProductTax),
            $attributeProductDescription ? new AttributeId($attributeProductDescription) : null,
            $attributeProductGallery? new AttributeId($attributeProductGallery) : null,
            $attributeProductMetaTitle? new AttributeId($attributeProductMetaTitle) : null,
            $attributeProductMetaDescription? new AttributeId($attributeProductMetaDescription) : null,
            $attributeProductKeywords? new AttributeId($attributeProductKeywords) : null,
            $categoryTree ? new CategoryTreeId($categoryTree) : null,
            $propertyGroup,
            $customField,
            $crossSellingObjects
        );
    }
}
