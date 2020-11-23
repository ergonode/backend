<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6CurrencyQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CurrencyRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Currency\GetCurrencyList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Currency\PostCurrencyCreate;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;

class CurrencySynchronizer implements SynchronizerInterface
{
    private Shopware6Connector $connector;

    private Shopware6CurrencyRepositoryInterface $currencyRepository;

    private AttributeRepositoryInterface $attributeRepository;

    private Shopware6CurrencyQueryInterface $currencyQuery;

    public function __construct(
        Shopware6Connector $connector,
        Shopware6CurrencyRepositoryInterface $currencyRepository,
        AttributeRepositoryInterface $attributeRepository,
        Shopware6CurrencyQueryInterface $currencyQuery
    ) {
        $this->connector = $connector;
        $this->currencyRepository = $currencyRepository;
        $this->attributeRepository = $attributeRepository;
        $this->currencyQuery = $currencyQuery;
    }

    public function synchronize(Export $export, Shopware6Channel $channel): void
    {
        $this->synchronizeShopware($channel);
        $this->checkExistOrCreate($channel);
    }

    private function synchronizeShopware(Shopware6Channel $channel): void
    {
        $start = new \DateTimeImmutable();
        $currencyList = $this->getShopwareCurrency($channel);
        foreach ($currencyList as $currency) {
            $this->currencyRepository->save($channel->getId(), $currency['iso'], $currency['id']);
        }
        $this->currencyQuery->cleanData($channel->getId(), $start);
    }

    /**
     * @return array
     */
    private function getShopwareCurrency(Shopware6Channel $channel): array
    {
        $query = new Shopware6QueryBuilder();
        $query->limit(500);
        $action = new GetCurrencyList($query);

        return $this->connector->execute($channel, $action);
    }

    private function checkExistOrCreate(Shopware6Channel $channel): void
    {
        /** @var PriceAttribute $attribute */
        $attribute = $this->attributeRepository->load($channel->getAttributeProductPriceGross());
        $iso = $attribute->getCurrency()->getCode();

        $isset = $this->currencyRepository->exists($channel->getId(), $iso);
        if ($isset) {
            return;
        }

        $action = new PostCurrencyCreate($iso);
        $this->connector->execute($channel, $action);

        $this->synchronizeShopware($channel);
    }
}
