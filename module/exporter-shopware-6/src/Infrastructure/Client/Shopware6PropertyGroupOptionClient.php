<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupOptionsRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\PostPropertyGroupOptionsAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

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
     * @var Shopware6PropertyGroupOptionsRepositoryInterface
     */
    private Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository;

    /**
     * @param Shopware6Connector                               $connector
     * @param Shopware6PropertyGroupRepositoryInterface        $propertyGroupRepository
     * @param Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository
     */
    public function __construct(
        Shopware6Connector $connector,
        Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository,
        Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository
    ) {
        $this->connector = $connector;
        $this->propertyGroupRepository = $propertyGroupRepository;
        $this->propertyGroupOptionsRepository = $propertyGroupOptionsRepository;
    }

    /**
     * @param Shopware6Channel             $channel
     * @param AttributeId                  $attributeId
     * @param Shopware6PropertyGroupOption $propertyGroupOption
     *
     * @return string
     */
    public function findByNameOrCreate(
        Shopware6Channel $channel,
        AttributeId $attributeId,
        Shopware6PropertyGroupOption $propertyGroupOption
    ): string {
        if ($this->propertyGroupOptionsRepository->exists(
            $channel->getId(),
            $attributeId,
            $propertyGroupOption->getName()
        )) {
            return $this->propertyGroupOptionsRepository->load(
                $channel->getId(),
                $attributeId,
                $propertyGroupOption->getName()
            );
        }

        return $this->createPropertyGroupOptions($channel, $attributeId, $propertyGroupOption);
    }

    /**
     * @param Shopware6Channel             $channel
     * @param AttributeId                  $attributeId
     * @param Shopware6PropertyGroupOption $propertyGroupOption
     *
     * @return string
     */
    private function createPropertyGroupOptions(
        Shopware6Channel $channel,
        AttributeId $attributeId,
        Shopware6PropertyGroupOption $propertyGroupOption
    ): string {
        $propertyGroupId = $this->propertyGroupRepository->load($channel->getId(), $attributeId);

        $action = new PostPropertyGroupOptionsAction($propertyGroupId, $propertyGroupOption, true);

        $option = $this->connector->execute($channel, $action);
        $this->propertyGroupOptionsRepository->save(
            $channel->getId(),
            $attributeId,
            $option->getName(),
            $option->getId()
        );

        return $option->getId();
    }
}
