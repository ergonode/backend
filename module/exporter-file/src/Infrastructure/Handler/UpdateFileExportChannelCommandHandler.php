<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler;

use Ergonode\ExporterFile\Domain\Command\UpdateFileExportChannelCommand;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class UpdateFileExportChannelCommandHandler
{
    private ChannelRepositoryInterface $repository;

    public function __construct(ChannelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateFileExportChannelCommand $command): void
    {
        /** @var FileExportChannel $channel */
        $channel = $this->repository->load($command->getId());
        $channel->setFormat($command->getFormat());
        $channel->setName($command->getName());
        $channel->setLanguages($command->getLanguages());
        $channel->setExportType($command->getExportType());

        $this->repository->save($channel);
    }
}
