<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalChannelMapper
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return array
     */
    public function map(AbstractChannel $channel): array
    {
        return [
            'id' => $channel->getId()->getValue(),
            'name' => $channel->getName(),
            'configuration' => $this->serializer->serialize($channel),
            'class' => \get_class($channel),
            'type' => $channel->getType(),
        ];
    }
}
