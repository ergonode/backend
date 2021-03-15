<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterMagento2\Infrastructure\Handler;

use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2CsvChannel;
use Ergonode\ExporterMagento2\Domain\Command\CreateMagento2ExportChannelCommand;

class CreateMagento2ChannelCommandHandler
{
    private ChannelRepositoryInterface $repository;

    public function __construct(ChannelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateMagento2ExportChannelCommand $command): void
    {
        $channel = new Magento2CsvChannel(
            $command->getId(),
            $command->getName(),
            $command->getFilename(),
            $command->getDefaultLanguage()
        );

        $this->repository->save($channel);
    }
}
