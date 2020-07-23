<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6TaxRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Tax\GetTaxList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Tax\PostTaxCreate;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Tax;
use Ergonode\Product\Domain\Query\AttributeValueQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

/**
 */
class TaxSynchronizer implements SynchronizerInterface
{
    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @var Shopware6TaxRepositoryInterface
     */
    private Shopware6TaxRepositoryInterface $taxRepository;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var AttributeValueQueryInterface
     */
    private AttributeValueQueryInterface $attributeValueQuery;

    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $optionQuery;

    /**
     * @param Shopware6Connector              $connector
     * @param Shopware6TaxRepositoryInterface $taxRepository
     * @param AttributeRepositoryInterface    $attributeRepository
     * @param AttributeValueQueryInterface    $attributeValueQuery
     * @param OptionQueryInterface            $optionQuery
     */
    public function __construct(
        Shopware6Connector $connector,
        Shopware6TaxRepositoryInterface $taxRepository,
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
     * @param ExportId         $id
     * @param Shopware6Channel $channel
     */
    public function synchronize(ExportId $id, Shopware6Channel $channel): void
    {
        $this->synchronizeShopware($channel);
        $this->checkExistOrCreate($channel);
    }

    /**
     * @param Shopware6Channel $channel
     */
    private function synchronizeShopware(Shopware6Channel $channel): void
    {
        $taxList = $this->getShopwareTax($channel);
        foreach ($taxList as $taxRow) {
            $this->taxRepository->save($channel->getId(), $taxRow->getRate(), $taxRow->getId());
        }
    }

    /**
     * @param Shopware6Channel $channel
     *
     * @return array|Shopware6Tax[]
     */
    private function getShopwareTax(Shopware6Channel $channel): array
    {
        $action = new GetTaxList();

        return $this->connector->execute($channel, $action);
    }

    /**
     * @param Shopware6Channel $channel
     */
    private function checkExistOrCreate(Shopware6Channel $channel): void
    {
        $value = [];
        $attribute = $this->attributeRepository->load($channel->getProductTax());
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
