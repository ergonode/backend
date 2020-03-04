<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository\Factory;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use JMS\Serializer\SerializerInterface;

/**
 */
class SourceFactory
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
     * @return AbstractSource
     */
    public function create(array $record): AbstractSource
    {
        $class = $record['type'];
        $data = $record['configuration'];

        return $this->serializer->deserialize($data, $class, 'json');
    }
}
