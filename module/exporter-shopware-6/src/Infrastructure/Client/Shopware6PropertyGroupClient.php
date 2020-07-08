<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\GetPropertyGroupList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\PostPropertyGroupAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;

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
     * @param Shopware6ExportApiProfile $profile
     *
     * @return Shopware6PropertyGroup[]|null
     */
    public function load(Shopware6ExportApiProfile $profile): ?array
    {
        $action = new GetPropertyGroupList();

        return $this->connector->execute($profile, $action);
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param string                    $name
     *
     * @return Shopware6PropertyGroup|null
     */
    public function findByName(Shopware6ExportApiProfile $profile, string $name): ?Shopware6PropertyGroup
    {
        $query = [
            [
                'query' => [
                    'type' => 'equals',
                    'field' => 'name',
                    'value' => $name,
                ],
                'sort' => [
                    'field' => 'createdAt',
                    'order' => 'DESC',
                ],
            ],
        ];

        $action = new GetPropertyGroupList($query, 1);

        $propertyList = $this->connector->execute($profile, $action);

        if (is_array($propertyList) && count($propertyList) > 0) {
            return $propertyList[0];
        }

        return null;
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param Shopware6PropertyGroup    $propertyGroup
     */
    public function insert(Shopware6ExportApiProfile $profile, Shopware6PropertyGroup $propertyGroup): void
    {
        $action = new PostPropertyGroupAction($propertyGroup);

        $this->connector->execute($profile, $action);
    }
}
