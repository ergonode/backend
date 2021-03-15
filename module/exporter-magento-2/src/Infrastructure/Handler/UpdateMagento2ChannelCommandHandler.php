<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterMagento2\Infrastructure\Handler;

use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2CsvChannel;
use Ergonode\ExporterMagento2\Domain\Command\UpdateMagento2ExportChannelCommand;

class UpdateMagento2ChannelCommandHandler
{
    private ChannelRepositoryInterface $repository;

    public function __construct(ChannelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    public function __invoke(UpdateMagento2ExportChannelCommand $command): void
    {
        /** @var Magento2CsvChannel $channel */
        $channel = $this->repository->load($command->getId());
        $channel->setFilename($command->getFilename());
        $channel->setDefaultLanguage($command->getDefaultLanguage());

        $this->repository->save($channel);
    }
}
