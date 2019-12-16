<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Provider;

use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\Channel\Domain\Provider\ChannelCollectionProviderInterface;
use Ergonode\Channel\Domain\Query\ChannelQueryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;

/**
 */
class ChannelCollectionProvider implements ChannelCollectionProviderInterface
{
    /**
     * @var ChannelQueryInterface
     */
    private $channelQuery;

    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    /**
     * @param ChannelQueryInterface      $channelQuery
     * @param ChannelRepositoryInterface $channelRepository
     */
    public function __construct(
        ChannelQueryInterface $channelQuery,
        ChannelRepositoryInterface $channelRepository
    ) {
        $this->channelQuery = $channelQuery;
        $this->channelRepository = $channelRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function provide(): array
    {
        $channels = $this->channelQuery->findAll();
        $channelList = [];

        foreach ($channels as $channel) {
            $channelList[] = $this->channelRepository->load(new ChannelId($channel['id']));
        }

        return $channelList;
    }
}
