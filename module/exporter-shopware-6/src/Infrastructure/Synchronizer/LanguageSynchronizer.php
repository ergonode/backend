<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6LanguageQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6LanguageClient;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class LanguageSynchronizer implements SynchronizerInterface
{
    /**
     * @var Shopware6LanguageClient
     */
    private Shopware6LanguageClient  $languageClient;

    /**
     * @var Shopware6LanguageRepositoryInterface
     */
    private Shopware6LanguageRepositoryInterface $languageShopwareRepository;

    /**
     * @var Shopware6LanguageQueryInterface
     */
    private Shopware6LanguageQueryInterface $languageShopwareQuery;

    /**
     * @param Shopware6LanguageClient              $languageClient
     * @param Shopware6LanguageRepositoryInterface $languageShopwareRepository
     * @param Shopware6LanguageQueryInterface      $languageShopwareQuery
     */
    public function __construct(
        Shopware6LanguageClient $languageClient,
        Shopware6LanguageRepositoryInterface $languageShopwareRepository,
        Shopware6LanguageQueryInterface $languageShopwareQuery
    ) {
        $this->languageClient = $languageClient;
        $this->languageShopwareRepository = $languageShopwareRepository;
        $this->languageShopwareQuery = $languageShopwareQuery;
    }

    /**
     * @param ExportId         $id
     * @param Shopware6Channel $channel
     */
    public function synchronize(ExportId $id, Shopware6Channel $channel): void
    {
        $this->synchronizeShopware($channel);
    }

    /**
     * @param Shopware6Channel $channel
     */
    private function synchronizeShopware(Shopware6Channel $channel): void
    {
        $start = new \DateTimeImmutable();
        $shopwareLanguageList = $this->languageClient->getLanguageList($channel);

        foreach ($shopwareLanguageList as $shopwareLanguage) {
            $this->languageShopwareRepository->save(
                $channel->getId(),
                $shopwareLanguage
            );
        }
        $this->languageShopwareQuery->cleanData($channel->getId(), $start);
    }
}
