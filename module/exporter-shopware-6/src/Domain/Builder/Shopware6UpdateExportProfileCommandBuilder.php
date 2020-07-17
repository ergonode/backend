<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Builder;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Exporter\Application\Provider\UpdateExportProfileCommandBuilderInterface;
use Ergonode\ExporterShopware6\Application\Form\Model\ExporterShopware6ConfigurationModel;
use Ergonode\ExporterShopware6\Domain\Command\UpdateShopware6ExportProfileCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Symfony\Component\Form\FormInterface;

/**
 */
class Shopware6UpdateExportProfileCommandBuilder implements UpdateExportProfileCommandBuilderInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return Shopware6ExportApiProfile::TYPE === $type;
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param FormInterface   $form
     *
     * @return DomainCommandInterface
     */
    public function build(ExportProfileId $exportProfileId, FormInterface $form): DomainCommandInterface
    {
        /** @var ExporterShopware6ConfigurationModel $data */
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
        $attributeProductPrice = $data->attributeProductPrice;
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

        return new UpdateShopware6ExportProfileCommand(
            $exportProfileId,
            $name,
            $host,
            $clientId,
            $clientKey,
            $defaultLanguage,
            $languages,
            $attributeProductName,
            $attributeProductActive,
            $attributeProductStock,
            $attributeProductPrice,
            $attributeProductTax,
            $attributeProductDescription,
            $categoryTree,
            $propertyGroup,
            $customField
        );
    }
}
