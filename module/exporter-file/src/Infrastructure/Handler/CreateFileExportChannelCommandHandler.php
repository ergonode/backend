<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler;

use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Domain\Command\CreateFileExportChannelCommand;

class CreateFileExportChannelCommandHandler
{
    private ChannelRepositoryInterface $repository;

    public function __construct(ChannelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateFileExportChannelCommand $command): void
    {
        $exportChannel = new FileExportChannel(
            $command->getId(),
            $command->getName(),
            $command->getFormat(),
            $command->getExportType(),
            $command->getLanguages()
        );

        $this->repository->save($exportChannel);
    }
}
