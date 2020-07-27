<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Persistence\Dbal\Repository\Mapper;

use JMS\Serializer\SerializerInterface;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

/**
 */
class ChannelMapper
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
