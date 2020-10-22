<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\GetPropertyGroup;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\GetPropertyGroupList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\PatchPropertyGroupAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\PostPropertyGroupAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

class Shopware6PropertyGroupClient
{
    private Shopware6Connector $connector;

    private Shopware6PropertyGroupRepositoryInterface $repository;

    public function __construct(Shopware6Connector $connector, Shopware6PropertyGroupRepositoryInterface $repository)
    {
        $this->connector = $connector;
        $this->repository = $repository;
    }

    /**
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
     * @return array|object|string|null
     */
    public function get(Shopware6Channel $channel, string $shopwareId, ?Shopware6Language $shopware6Language = null)
    {
        $action = new GetPropertyGroup($shopwareId);
        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }

        return $this->connector->execute($channel, $action);
    }

    public function insert(
        Shopware6Channel $channel,
        Shopware6PropertyGroup $propertyGroup,
        AbstractAttribute $attribute
    ): ?Shopware6PropertyGroup {
        $action = new PostPropertyGroupAction($propertyGroup, true);

        $shopwarePropertyGroup = $this->connector->execute($channel, $action);
        $this->repository->save(
            $channel->getId(),
            $attribute->getId(),
            $shopwarePropertyGroup->getId(),
            $attribute->getType()
        );

        return $shopwarePropertyGroup;
    }

    public function update(
        Shopware6Channel $channel,
        Shopware6PropertyGroup $propertyGroup,
        ?Shopware6Language $shopware6Language = null
    ): void {
        $action = new PatchPropertyGroupAction($propertyGroup);
        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }
        $this->connector->execute($channel, $action);
    }

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
}
