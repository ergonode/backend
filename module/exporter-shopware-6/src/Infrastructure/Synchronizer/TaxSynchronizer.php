<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6TaxQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6TaxRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Tax\GetTaxList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Tax\PostTaxCreate;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Tax;
use Ergonode\Product\Domain\Query\AttributeValueQueryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

class TaxSynchronizer implements SynchronizerInterface
{
    private Shopware6Connector $connector;

    private Shopware6TaxRepositoryInterface $taxRepository;

    private AttributeRepositoryInterface $attributeRepository;

    private AttributeValueQueryInterface $attributeValueQuery;

    private OptionQueryInterface $optionQuery;

    private Shopware6TaxQueryInterface $taxQueryInterface;

    public function __construct(
        Shopware6Connector $connector,
        Shopware6TaxRepositoryInterface $taxRepository,
        AttributeRepositoryInterface $attributeRepository,
        AttributeValueQueryInterface $attributeValueQuery,
        OptionQueryInterface $optionQuery,
        Shopware6TaxQueryInterface $taxQueryInterface
    ) {
        $this->connector = $connector;
        $this->taxRepository = $taxRepository;
        $this->attributeRepository = $attributeRepository;
        $this->attributeValueQuery = $attributeValueQuery;
        $this->optionQuery = $optionQuery;
        $this->taxQueryInterface = $taxQueryInterface;
    }


    public function synchronize(Export $export, Shopware6Channel $channel): void
    {
        $this->synchronizeShopware($channel);
        $this->checkExistOrCreate($channel);
    }

    private function synchronizeShopware(Shopware6Channel $channel): void
    {
        $start = new \DateTimeImmutable();
        $taxList = $this->getShopwareTax($channel);
        foreach ($taxList as $taxRow) {
            $this->taxRepository->save($channel->getId(), $taxRow->getRate(), $taxRow->getId());
        }
        $this->taxQueryInterface->cleanData($channel->getId(), $start);
    }

    /**
     * @return array|Shopware6Tax[]
     */
    private function getShopwareTax(Shopware6Channel $channel): array
    {
        $query = new Shopware6QueryBuilder();
        $query->limit(500);
        $action = new GetTaxList($query);

        return $this->connector->execute($channel, $action);
    }

    private function checkExistOrCreate(Shopware6Channel $channel): void
    {
        $value = [];
        $attribute = $this->attributeRepository->load($channel->getAttributeProductTax());
        if ($attribute instanceof SelectAttribute || $attribute instanceof MultiSelectAttribute) {
            $data = $this->optionQuery->getAll($attribute->getId());
            foreach ($data as $row) {
                $value[] = (float) $row['code'];
            }
        } else {
            $data = $this->attributeValueQuery->getUniqueValue($attribute->getId());
            foreach ($data as $row) {
                $value[] = (float) $row;
            }
        }
        $update = false;
        foreach ($value as $tax) {
            $isset = $this->taxRepository->exists($channel->getId(), $tax);
            if ($isset) {
                continue;
            }
            $action = new PostTaxCreate(
                new Shopware6Tax(
                    null,
                    $tax,
                    $tax.'%'
                )
            );
            $this->connector->execute($channel, $action);
            $update = true;
        }

        if ($update) {
            $this->synchronizeShopware($channel);
        }
    }
}
