<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\GetPropertyGroupList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\PostPropertyGroupAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

/**
 */
class Shopware6PropertyGroupClient
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
     * @return Shopware6PropertyGroup[]|null
     */
    public function load(Shopware6Channel $channel): ?array
    {
        $query = new Shopware6QueryBuilder();
        $query->limit(500);
        $action = new GetPropertyGroupList($query);

        return $this->connector->execute($channel, $action);
    }

    /**
     * @param Shopware6Channel $channel
     * @param string           $name
     *
     * @return Shopware6PropertyGroup|null
     */
    public function findByName(Shopware6Channel $channel, string $name): ?Shopware6PropertyGroup
    {
        $query = new Shopware6QueryBuilder();
        $query->equals('name', $name)
            ->sort('createdAt', 'DESC')
            ->limit(1);

        $action = new GetPropertyGroupList($query);

        $propertyList = $this->connector->execute($channel, $action);

        if (is_array($propertyList) && count($propertyList) > 0) {
            return $propertyList[0];
        }

        return null;
    }

    /**
     * @param Shopware6Channel       $channel
     * @param Shopware6PropertyGroup $propertyGroup
     *
     * @return Shopware6PropertyGroup|null
     */
    public function createPropertyGroupResource(
        Shopware6Channel $channel,
        Shopware6PropertyGroup $propertyGroup
    ): ?Shopware6PropertyGroup {
        $action = new PostPropertyGroupAction($propertyGroup, true);

        return $this->connector->execute($channel, $action);
    }
}
