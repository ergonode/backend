<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField\GetCustomFieldSetList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField\PostCustomFieldSetAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomFieldSet;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

class Shopware6CustomFieldSetClient
{

    private Shopware6Connector $connector;

    public function __construct(Shopware6Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * @return array|null
     */
    public function load(Shopware6Channel $channel): ?array
    {
        $query = new Shopware6QueryBuilder();
        $query->limit(500);

        $action = new GetCustomFieldSetList($query);

        return $this->connector->execute($channel, $action);
    }

    public function insert(
        Shopware6Channel $channel,
        AbstractShopware6CustomFieldSet $customFieldSet
    ): ?AbstractShopware6CustomFieldSet {
        $action = new PostCustomFieldSetAction($customFieldSet, true);

        return $this->connector->execute($channel, $action);
    }

    public function findByCode(Shopware6Channel $channel, string $code): ?AbstractShopware6CustomFieldSet
    {
        $query = new Shopware6QueryBuilder();
        $query->equals('name', $code)
            ->sort('createdAt', 'DESC')
            ->limit(1);

        $action = new GetCustomFieldSetList($query);
        $customFieldList = $this->connector->execute($channel, $action);
        if (is_array($customFieldList) && count($customFieldList) > 0) {
            return $customFieldList[0];
        }

        return null;
    }
}
