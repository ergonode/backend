<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Ergonode\Channel\Domain\Command\DeleteChannelCommand;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Webmozart\Assert\Assert;

class DeleteChannelCommandHandler
{
    private ChannelRepositoryInterface $repository;

    public function __construct(ChannelRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteChannelCommand $command): void
    {
        $channel = $this->repository->load($command->getId());

        Assert::notNull($channel, sprintf('Can\'t fid channel "%s"', $command->getId()->getValue()));

        $this->repository->delete($channel);
    }
}
