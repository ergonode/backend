<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupOptionsRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\GetPropertyGroupOptions;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\PatchPropertyGroupOptionAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PropertyGroup\PostPropertyGroupOptionsAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
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
     * @var Shopware6PropertyGroupOptionsRepositoryInterface
     */
    private Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository;

    /**
     * @param Shopware6Connector                               $connector
     * @param Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository
     */
    public function __construct(
        Shopware6Connector $connector,
        Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository
    ) {
        $this->connector = $connector;
        $this->propertyGroupOptionsRepository = $propertyGroupOptionsRepository;
    }

    /**
     * @param Shopware6Channel       $channel
     * @param string                 $propertyGroupId
     * @param string                 $propertyGroupOptionId
     * @param Shopware6Language|null $shopware6Language
     *
     * @return array|object|string|null
     */
    public function get(
        Shopware6Channel $channel,
        string $propertyGroupId,
        string $propertyGroupOptionId,
        ?Shopware6Language $shopware6Language = null
    ) {
        $action = new GetPropertyGroupOptions($propertyGroupId, $propertyGroupOptionId);
        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }

        return $this->connector->execute($channel, $action);
    }

    /**
     * @param Shopware6Channel             $channel
     * @param string                       $propertyGroupId
     * @param Shopware6PropertyGroupOption $propertyGroupOption
     * @param AbstractOption               $option
     *
     * @return Shopware6PropertyGroupOption|null
     */
    public function insert(
        Shopware6Channel $channel,
        string $propertyGroupId,
        Shopware6PropertyGroupOption $propertyGroupOption,
        AbstractOption $option
    ): ?Shopware6PropertyGroupOption {
        $action = new PostPropertyGroupOptionsAction($propertyGroupId, $propertyGroupOption, true);

        $shopwarePropertyGroupOptions = $this->connector->execute($channel, $action);

        $this->propertyGroupOptionsRepository->save(
            $channel->getId(),
            $option->getAttributeId(),
            $option->getId(),
            $shopwarePropertyGroupOptions->getId()
        );

        return $shopwarePropertyGroupOptions;
    }

    /**
     * @param Shopware6Channel             $channel
     * @param string                       $propertyGroupId
     * @param Shopware6PropertyGroupOption $propertyGroupOption
     * @param Shopware6Language|null       $shopware6Language
     */
    public function update(
        Shopware6Channel $channel,
        string $propertyGroupId,
        Shopware6PropertyGroupOption $propertyGroupOption,
        ?Shopware6Language $shopware6Language = null
    ): void {
        $action = new PatchPropertyGroupOptionAction($propertyGroupId, $propertyGroupOption);
        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }
        $this->connector->execute($channel, $action);
    }
}
