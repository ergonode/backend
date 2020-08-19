<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler;

use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\CreateShopware6ChannelCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

/**
 */
class CreateShopware6ChannelCommandHandler
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
     * @param CreateShopware6ChannelCommand $command
     */
    public function __invoke(CreateShopware6ChannelCommand $command)
    {
        $channel = new Shopware6Channel(
            $command->getId(),
            $command->getName(),
            $command->getHost(),
            $command->getClientId(),
            $command->getClientKey(),
            $command->getDefaultLanguage(),
            $command->getLanguages(),
            $command->getProductName(),
            $command->getProductActive(),
            $command->getProductStock(),
            $command->getProductPriceGross(),
            $command->getProductPriceNet(),
            $command->getProductTax(),
            $command->getProductDescription(),
            $command->getCategoryTree(),
            $command->getPropertyGroup(),
            $command->getCustomField()
        );

        $this->repository->save($channel);
    }
}
