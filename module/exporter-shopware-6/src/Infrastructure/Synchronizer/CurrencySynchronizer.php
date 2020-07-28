<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CurrencyRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Currency\GetCurrencyList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Currency\PostCurrencyCreate;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class CurrencySynchronizer implements SynchronizerInterface
{
    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @var Shopware6CurrencyRepositoryInterface
     */
    private Shopware6CurrencyRepositoryInterface $currencyRepository;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @param Shopware6Connector                   $connector
     * @param Shopware6CurrencyRepositoryInterface $currencyRepository
     * @param AttributeRepositoryInterface         $attributeRepository
     */
    public function __construct(
        Shopware6Connector $connector,
        Shopware6CurrencyRepositoryInterface $currencyRepository,
        AttributeRepositoryInterface $attributeRepository
    ) {
        $this->connector = $connector;
        $this->currencyRepository = $currencyRepository;
        $this->attributeRepository = $attributeRepository;
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
        $currencyList = $this->getShopwareCurrency($channel);
        foreach ($currencyList as $currency) {
            $this->currencyRepository->save($channel->getId(), $currency['iso'], $currency['id']);
        }
    }

    /**
     * @param Shopware6Channel $channel
     *
     * @return array
     */
    private function getShopwareCurrency(Shopware6Channel $channel): array
    {
        $action = new GetCurrencyList();

        return $this->connector->execute($channel, $action);
    }

    /**
     * @param Shopware6Channel $channel
     */
    private function checkExistOrCreate(Shopware6Channel $channel): void
    {
        /** @var PriceAttribute $attribute */
        $attribute = $this->attributeRepository->load($channel->getProductPriceGross());
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
