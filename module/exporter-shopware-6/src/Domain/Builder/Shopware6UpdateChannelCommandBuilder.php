<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Builder;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Symfony\Component\Form\FormInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\Channel\Application\Provider\UpdateChannelCommandBuilderInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\ExporterShopware6\Domain\Command\UpdateShopware6ChannelCommand;
use Ergonode\ExporterShopware6\Application\Model\Shopware6ChannelFormModel;

/**
 */
class Shopware6UpdateChannelCommandBuilder implements UpdateChannelCommandBuilderInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return Shopware6Channel::TYPE === $type;
    }

    /**
     * @param ChannelId     $id
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     */
    public function build(ChannelId $id, FormInterface $form): DomainCommandInterface
    {
        /** @var Shopware6ChannelFormModel $data */
        $data = $form->getData();

        $name = $data->name;
        $host = $data->host;
        $clientId = $data->clientId;
        $clientKey = $data->clientKey;
        $defaultLanguage = $data->defaultLanguage;
        $languages = $data->languages;
        $attributeProductName = $data->attributeProductName;
        $attributeProductActive = $data->attributeProductActive;
        $attributeProductStock = $data->attributeProductStock;
        $attributeProductPriceGross = $data->attributeProductPriceGross;
        $attributeProductPriceNet = $data->attributeProductPriceNet;
        $attributeProductTax = $data->attributeProductTax;
        $attributeProductDescription = $data->attributeProductDescription;
        $categoryTree = $data->categoryTree;

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

        return new UpdateShopware6ChannelCommand(
            $id,
            $name,
            $host,
            $clientId,
            $clientKey,
            new Language($defaultLanguage),
            $languageObjects,
            $attributeProductName,
            $attributeProductActive,
            $attributeProductStock,
            $attributeProductPriceGross,
            $attributeProductPriceNet,
            $attributeProductTax,
            $attributeProductDescription,
            $categoryTree ? new CategoryTreeId($categoryTree) : null,
            $propertyGroup,
            $customField
        );
    }
}
