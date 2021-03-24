<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Language\GetLanguageList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Language\GetLocate;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Locate;

class Shopware6LanguageClient
{
    private Shopware6Connector $connector;

    public function __construct(Shopware6Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * @return Shopware6Language[]
     */
    public function getLanguageList(Shopware6Channel $channel): array
    {
        $query = new Shopware6QueryBuilder();
        $query->limit(500);

        $action = new GetLanguageList($query);

        $languageList = $this->connector->execute($channel, $action);

        /** @var Shopware6Language $language */
        foreach ($languageList as $language) {
            $locate = $this->getLocate($channel, $language->getTranslationCodeId());
            $language->setIso(str_replace('-', '_', $locate->getCode()));
        }

        return $languageList;
    }

    private function getLocate(Shopware6Channel $channel, string $locateId): Shopware6Locate
    {
        $action = new GetLocate($locateId);

        return $this->connector->execute($channel, $action);
    }
}
