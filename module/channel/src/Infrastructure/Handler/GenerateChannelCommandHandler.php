<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Ergonode\Channel\Domain\Command\GenerateChannelCommand;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Channel\Infrastructure\Exception\ChannelGeneratorProviderNotFoundException;
use Ergonode\Channel\Infrastructure\Provider\ChannelGeneratorProvider;

/**
 */
class GenerateChannelCommandHandler
{
    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var ChannelGeneratorProvider
     */
    private ChannelGeneratorProvider $provider;

    /**
     * @param ChannelRepositoryInterface $channelRepository
     * @param ChannelGeneratorProvider   $provider
     */
    public function __construct(ChannelRepositoryInterface $channelRepository, ChannelGeneratorProvider $provider)
    {
        $this->channelRepository = $channelRepository;
        $this->provider = $provider;
    }

    /**
     * @param GenerateChannelCommand $command
     *
     * @throws ChannelGeneratorProviderNotFoundException
     */
    public function __invoke(GenerateChannelCommand $command)
    {
        $generator = $this->provider->provide($command->getType());
        $channel = $generator->generate($command->getId(), $command->getName());

        $this->channelRepository->save($channel);
    }
}
