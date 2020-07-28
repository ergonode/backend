<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler;

use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\UpdateShopware6ChannelCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

/**
 */
class UpdateShopware6ChannelCommandHandler
{
    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $repository;

    /**
     * @param ChannelRepositoryInterface $repository
     */
    public function __construct(ChannelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateShopware6ChannelCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateShopware6ChannelCommand $command)
    {
        /** @var Shopware6Channel $channel */
        $channel = $this->repository->load($command->getId());
        $channel->setName($command->getName());
        $channel->setHost($command->getHost());
        $channel->setClientId($command->getClientId());
        $channel->setClientKey($command->getClientKey());
        $channel->setDefaultLanguage($command->getDefaultLanguage());
        $channel->setLanguages($command->getLanguages());
        $channel->setProductName($command->getProductName());
        $channel->setProductActive($command->getProductActive());
        $channel->setProductStock($command->getProductStock());
        $channel->setProductPrice($command->getProductPrice());
        $channel->setProductTax($command->getProductTax());
        $channel->setProductDescription($command->getProductDescription());
        $channel->setCategoryTree($command->getCategoryTree());
        $channel->setPropertyGroup($command->getPropertyGroup());
        $channel->setCustomField($command->getCustomField());

        $this->repository->save($channel);
    }
}
