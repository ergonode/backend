<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Handler;

use Ergonode\ExporterFile\Domain\Command\UpdateFileExportChannelCommand;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

/**
 */
class UpdateFileExportChannelCommandHandler
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
     * @param UpdateFileExportChannelCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateFileExportChannelCommand $command)
    {
        /** @var FileExportChannel $channel */
        $channel = $this->repository->load($command->getId());
        $channel->setFormat($command->getFormat());
        $channel->setName($command->getName());

        $this->repository->save($channel);
    }
}
