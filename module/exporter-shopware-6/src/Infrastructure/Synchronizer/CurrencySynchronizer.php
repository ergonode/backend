<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
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
        $currencyList = $this->getShopwareCurrency($profile);
        foreach ($currencyList as $currency) {
            $this->currencyRepository->save($profile->getId(), $currency['iso'], $currency['id']);
        }
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     *
     * @return array
     */
    private function getShopwareCurrency(Shopware6ExportApiProfile $profile): array
    {
        $action = new GetCurrencyList();

        return $this->connector->execute($profile, $action);
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     */
    private function checkExistOrCreate(Shopware6ExportApiProfile $profile): void
    {
        /** @var PriceAttribute $attribute */
        $attribute = $this->attributeRepository->load($profile->getProductPrice());
        $iso = $attribute->getCurrency()->getCode();

        $isset = $this->currencyRepository->exists($profile->getId(), $iso);
        if ($isset) {
            return;
        }

        $action = new PostCurrencyCreate($iso);
        $this->connector->execute($profile, $action);

        $this->synchronizeShopware($profile);
    }
}
