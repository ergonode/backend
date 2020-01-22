<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Ergonode\Channel\Domain\Command\CreateChannelCommand;
use Ergonode\Channel\Domain\Factory\ChannelFactory;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;

/**
 */
class CreateChannelCommandHandler
{
    /**
     * @var ChannelFactory
     */
    private ChannelFactory $factory;

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $repository;

    /**
     * @param ChannelFactory             $factory
     * @param ChannelRepositoryInterface $repository
     */
    public function __construct(ChannelFactory $factory, ChannelRepositoryInterface $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @param CreateChannelCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateChannelCommand $command)
    {
        $channel = $this->factory->create(
            $command->getId(),
            $command->getName(),
            $command->getSegmentId()
        );

        $this->repository->save($channel);
    }
}
