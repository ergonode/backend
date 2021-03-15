<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Repository\Factory;

use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalChannelFactory
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param array $record
     */
    public function create(array $record): AbstractChannel
    {
        $class = $record['class'];
        $data = $record['configuration'];

        return $this->serializer->deserialize($data, $class);
    }
}
