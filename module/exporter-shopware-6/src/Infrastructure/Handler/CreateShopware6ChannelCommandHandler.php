<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler;

use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\CreateShopware6ChannelCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

class CreateShopware6ChannelCommandHandler
{
    private ChannelRepositoryInterface $repository;

    public function __construct(ChannelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateShopware6ChannelCommand $command): void
    {
        $channel = new Shopware6Channel(
            $command->getId(),
            $command->getName(),
            $command->getHost(),
            $command->getClientId(),
            $command->getClientKey(),
            $command->getSegment(),
            $command->getDefaultLanguage(),
            $command->getLanguages(),
            $command->getProductName(),
            $command->getProductActive(),
            $command->getProductStock(),
            $command->getProductPriceGross(),
            $command->getProductPriceNet(),
            $command->getProductTax(),
            $command->getProductDescription(),
            $command->getProductGallery(),
            $command->getProductMetaTitle(),
            $command->getProductMetaDescription(),
            $command->getProductKeywords(),
            $command->getCategoryTree(),
            $command->getPropertyGroup(),
            $command->getCustomField(),
            $command->getCrossSelling()
        );

        $this->repository->save($channel);
    }
}
