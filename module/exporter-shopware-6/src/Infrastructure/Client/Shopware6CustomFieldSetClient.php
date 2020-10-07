<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField\GetCustomFieldSetList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField\PostCustomFieldSetAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomFieldSet;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

/**
 */
class Shopware6CustomFieldSetClient
{

    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @param Shopware6Connector $connector
     */
    public function __construct(Shopware6Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * @param Shopware6Channel $channel
     *
     * @return array|null
     */
    public function load(Shopware6Channel $channel): ?array
    {
        $query = new Shopware6QueryBuilder();
        $query->limit(500);

        $action = new GetCustomFieldSetList($query);

        return $this->connector->execute($channel, $action);
    }

    /**
     * @param Shopware6Channel        $channel
     * @param Shopware6CustomFieldSet $customFieldSet
     *
     * @return Shopware6CustomFieldSet|null
     */
    public function insert(Shopware6Channel $channel, Shopware6CustomFieldSet $customFieldSet): ?Shopware6CustomFieldSet
    {
        $action = new PostCustomFieldSetAction($customFieldSet, true);

        return $this->connector->execute($channel, $action);
    }

    /**
     * @param Shopware6Channel $channel
     * @param string           $code
     *
     * @return Shopware6CustomFieldSet|null
     */
    public function findByCode(Shopware6Channel $channel, string $code): ?Shopware6CustomFieldSet
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
