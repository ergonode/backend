<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Core\Domain\Repository\LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Language\GetLanguageList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class LanguageSynchronizer implements SynchronizerInterface
{
    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @var Shopware6LanguageRepositoryInterface
     */
    private Shopware6LanguageRepositoryInterface $languageShopwareRepository;

    /**
     *
     * /**
     * @param Shopware6Connector                   $connector
     * @param Shopware6LanguageRepositoryInterface $languageShopwareRepository
     */
    public function __construct(
        Shopware6Connector $connector,
        Shopware6LanguageRepositoryInterface $languageShopwareRepository
    ) {
        $this->connector = $connector;
        $this->languageShopwareRepository = $languageShopwareRepository;
    }

    /**
     * @param ExportId                  $id
     * @param Shopware6ExportApiProfile $profile
     */
    public function synchronize(ExportId $id, Shopware6ExportApiProfile $profile): void
    {
        $this->synchronizeShopware($profile);
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     */
    private function synchronizeShopware(Shopware6ExportApiProfile $profile): void
    {
        $languageList = $this->getShopwareLanguageList($profile);
        foreach ($languageList as $language) {
            $this->languageShopwareRepository->save($profile->getId(), $language['name'], $language['id']);
        }
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     *
     * @return array
     */
    private function getShopwareLanguageList(Shopware6ExportApiProfile $profile): array
    {
        $action = new GetLanguageList();

        return $this->connector->execute($profile, $action);
    }
}
