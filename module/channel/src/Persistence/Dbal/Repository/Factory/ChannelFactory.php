<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Persistence\Dbal\Repository\Factory;

use JMS\Serializer\SerializerInterface;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

/**
 */
class ChannelFactory
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
     * @param array $record
     *
     * @return AbstractChannel
     */
    public function create(array $record): AbstractChannel
    {
        $class = $record['class'];
        $data = $record['configuration'];

        return $this->serializer->deserialize($data, $class, 'json');
    }
}
