<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6PropertyGroupQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\GetPropertyGroupOptionsList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\PostPropertyGroupOptionsAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

/**
 */
class Shopware6PropertyGroupOptionClient
{
    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;
    /**
     * @var Shopware6PropertyGroupRepositoryInterface
     */
    private Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository;

    /**
     * @param Shopware6Connector                        $connector
     * @param Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository
     */
    public function __construct(
        Shopware6Connector $connector,
        Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository
    ) {
        $this->connector = $connector;
        $this->propertyGroupRepository = $propertyGroupRepository;
    }


    /**
     * @param Shopware6ExportApiProfile $profile
     * @param string                    $propertyGroupId
     * @param string                    $name
     *
     * @return Shopware6PropertyGroupOption|null
     */
    public function findByName(
        Shopware6ExportApiProfile $profile,
        string $propertyGroupId,
        string $name
    ): ?Shopware6PropertyGroupOption {
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

        $action = new GetPropertyGroupOptionsList($propertyGroupId, $query, 1);

        $propertyList = $this->connector->execute($profile, $action);

        if (is_array($propertyList) && count($propertyList) > 0) {
            return $propertyList[0];
        }

        return null;
    }


    /**
     * @param Shopware6ExportApiProfile $profile
     * @param string                    $propertyGroupId
     * @param string                    $name
     */
    public function insert(Shopware6ExportApiProfile $profile, string $propertyGroupId, string $name): void
    {
        $action = new PostPropertyGroupOptionsAction($propertyGroupId, $name);

        $this->connector->execute($profile, $action);
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param AttributeId               $attributeId
     * @param string                    $name
     *
     * @return Shopware6PropertyGroupOption
     */
    public function findByNameOrCreate(
        Shopware6ExportApiProfile $profile,
        AttributeId $attributeId,
        string $name
    ): Shopware6PropertyGroupOption {
        $propertyGroupId = $this->propertyGroupRepository->load($profile->getId(), $attributeId);
//        if(!n)

        $propertyGroupOption = $this->findByName($profile, $propertyGroupId, $name);
        if ($propertyGroupOption) {
            return $propertyGroupOption;
        }

        $this->insert($profile, $propertyGroupId, $name);

        $propertyGroupOption = $this->findByName($profile, $propertyGroupId, $name);

        return $propertyGroupOption;
    }
}
