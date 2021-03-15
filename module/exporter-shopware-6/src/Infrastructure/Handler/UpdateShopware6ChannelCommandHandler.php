<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler;

use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\UpdateShopware6ChannelCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

class UpdateShopware6ChannelCommandHandler
{
    private ChannelRepositoryInterface $repository;

    public function __construct(ChannelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateShopware6ChannelCommand $command): void
    {
        /** @var Shopware6Channel $channel */
        $channel = $this->repository->load($command->getId());
        $channel->setName($command->getName());
        $channel->setHost($command->getHost());
        $channel->setClientId($command->getClientId());
        $channel->setClientKey($command->getClientKey());
        $channel->setSegment($command->getSegment());
        $channel->setDefaultLanguage($command->getDefaultLanguage());
        $channel->setLanguages($command->getLanguages());
        $channel->setAttributeProductName($command->getProductName());
        $channel->setAttributeProductActive($command->getProductActive());
        $channel->setAttributeProductStock($command->getProductStock());
        $channel->setAttributeProductPriceGross($command->getProductPriceGross());
        $channel->setAttributeProductPriceNet($command->getProductPriceNet());
        $channel->setAttributeProductTax($command->getProductTax());
        $channel->setAttributeProductDescription($command->getProductDescription());
        $channel->setAttributeProductGallery($command->getProductGallery());
        $channel->setAttributeProductMetaTitle($command->getProductMetaTitle());
        $channel->setAttributeProductMetaDescription($command->getProductMetaDescription());
        $channel->setAttributeProductKeywords($command->getProductKeywords());
        $channel->setCategoryTree($command->getCategoryTree());
        $channel->setPropertyGroup($command->getPropertyGroup());
        $channel->setCustomField($command->getCustomField());
        $channel->setCrossSelling($command->getCrossSelling());

        $this->repository->save($channel);
    }
}
