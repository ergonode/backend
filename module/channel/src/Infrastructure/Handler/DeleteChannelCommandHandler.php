<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Ergonode\Channel\Domain\Command\DeleteChannelCommand;
use Ergonode\Channel\Domain\Query\ExportQueryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use League\Flysystem\FilesystemInterface;
use Webmozart\Assert\Assert;

class DeleteChannelCommandHandler
{
    private ChannelRepositoryInterface $channelRepository;

    private ExportQueryInterface $exportQuery;

    private FilesystemInterface $exportStorage;

    public function __construct(
        ChannelRepositoryInterface $channelRepository,
        ExportQueryInterface $exportQuery,
        FilesystemInterface $exportStorage
    ) {
        $this->channelRepository = $channelRepository;
        $this->exportQuery = $exportQuery;
        $this->exportStorage = $exportStorage;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteChannelCommand $command): void
    {
        $channel = $this->channelRepository->load($command->getId());

        Assert::notNull($channel, sprintf('Can\'t fid channel "%s"', $command->getId()->getValue()));

        $exportIds = $this->exportQuery->getExportIdsByChannelId($channel->getId());
        foreach ($exportIds as $exportId) {
            $file = sprintf('%s.zip', $exportId);
            if ($this->exportStorage->has($file)) {
                $this->exportStorage->delete($file);
            }
        }

        $this->channelRepository->delete($channel);
    }
}
