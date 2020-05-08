<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Command\Export\Shopware6ExportTreeCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ChannelApiProfile;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;

/**
 */
class Shopware6CategoryTreeProcessor
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param Export                     $export
     * @param Shopware6ExportApiProfile  $exportProfile
     * @param Shopware6ChannelApiProfile $channelApiProfile
     */
    public function process(
        Export $export,
        Shopware6ExportApiProfile $exportProfile,
        Shopware6ChannelApiProfile $channelApiProfile
    ) {
        if ($channelApiProfile->getCategoryTreeId()) {
            $this->commandBus->dispatch(
                new Shopware6ExportTreeCommand($export->getId())
            );
        }
    }
}
