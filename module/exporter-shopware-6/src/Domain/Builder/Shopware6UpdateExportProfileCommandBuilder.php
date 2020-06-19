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
        $language = $data->defaultLanguage;
        $attributeProductName = $data->attributeProductName;
        $attributeProductActive = $data->attributeProductActive;
        $attributeProductStock = $data->attributeProductStock;
        $attributeProductPrice = $data->attributeProductPrice;
        $attributeProductTax = $data->attributeProductTax;
        $categoryTree = $data->categoryTree;

        return new UpdateShopware6ExportProfileCommand(
            $exportProfileId,
            $name,
            $host,
            $clientId,
            $clientKey,
            $language,
            $attributeProductName,
            $attributeProductActive,
            $attributeProductStock,
            $attributeProductPrice,
            $attributeProductTax,
            $categoryTree,
            []
        );
    }
}
