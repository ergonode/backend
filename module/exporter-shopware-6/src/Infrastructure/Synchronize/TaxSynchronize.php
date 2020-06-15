<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronize;

use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Query\AttributeValueQueryInterface;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Domain\Repository\Shopwer6TaxRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Tax\GetTaxList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Tax\PostTaxCreate;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class TaxSynchronize implements SynchronizeInterface
{
    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @var Shopwer6TaxRepositoryInterface
     */
    private Shopwer6TaxRepositoryInterface $taxRepository;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var AttributeValueQueryInterface
     */
    private AttributeValueQueryInterface $attributeValueQuery;

    private OptionQueryInterface $optionQuery;

    /**
     * @param Shopware6Connector             $connector
     * @param Shopwer6TaxRepositoryInterface $taxRepository
     * @param AttributeRepositoryInterface   $attributeRepository
     * @param AttributeValueQueryInterface   $attributeValueQuery
     * @param OptionQueryInterface           $optionQuery
     */
    public function __construct(
        Shopware6Connector $connector,
        Shopwer6TaxRepositoryInterface $taxRepository,
        AttributeRepositoryInterface $attributeRepository,
        AttributeValueQueryInterface $attributeValueQuery,
        OptionQueryInterface $optionQuery
    ) {
        $this->connector = $connector;
        $this->taxRepository = $taxRepository;
        $this->attributeRepository = $attributeRepository;
        $this->attributeValueQuery = $attributeValueQuery;
        $this->optionQuery = $optionQuery;
    }


    /**
     * @param ExportId                  $id
     * @param Shopware6ExportApiProfile $profile
     */
    public function synchronize(ExportId $id, Shopware6ExportApiProfile $profile): void
    {
        $this->synchronizeShopware($profile);
        $this->checkExistOrCreate($profile);
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     */
    private function synchronizeShopware(Shopware6ExportApiProfile $profile): void
    {
        $taxList = $this->getShopwareTax($profile);
        foreach ($taxList as $taxRow) {
            $this->taxRepository->save($profile->getId(), $taxRow['tax'], $taxRow['id']);
        }
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     *
     * @return array
     */
    private function getShopwareTax(Shopware6ExportApiProfile $profile): array
    {
        $action = new GetTaxList();

        return $this->connector->execute($profile, $action);
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     */
    private function checkExistOrCreate(Shopware6ExportApiProfile $profile): void
    {
        $value = [];
        $attribute = $this->attributeRepository->load($profile->getProductTax());
        if ($attribute instanceof SelectAttribute || $attribute instanceof MultiSelectAttribute) {
            $data = $this->optionQuery->getAll($attribute->getId());
            foreach ($data as $row) {
                $value[] = floatval($row['code']);
            }
        } else {
            $data = $this->attributeValueQuery->getUniqueValue($attribute->getId());
            foreach ($data as $row) {
                $value[] = floatval($row);
            }
        }
        $update = false;
        foreach ($value as $tax) {
            $isset = $this->taxRepository->exists($profile->getId(), $tax);
            if ($isset) {
                continue;
            }
            $action = new PostTaxCreate($tax);
            $this->connector->execute($profile, $action);
            $update = true;
        }

        if ($update) {
            $this->synchronizeShopware($profile);
        }
    }
}
