<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\Channel\Domain\Entity\AbstractChannel;
use JMS\Serializer\SerializerInterface;

class DbalChannelMapper
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param AbstractChannel $channel
     *
     * @return array
     */
    public function map(AbstractChannel $channel): array
    {
        return [
            'id' => $channel->getId()->getValue(),
            'name' => $channel->getName(),
            'configuration' => $this->serializer->serialize($channel, 'json'),
            'class' => \get_class($channel),
            'type' => $channel->getType(),
        ];
    }
}
