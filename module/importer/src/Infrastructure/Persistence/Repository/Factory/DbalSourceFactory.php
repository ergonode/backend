<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Repository\Factory;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Core\Application\Serializer\SerializerInterface;

class DbalSourceFactory
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param array $record
     */
    public function create(array $record): AbstractSource
    {
        $class = $record['class'];
        $data = $record['configuration'];

        return $this->serializer->deserialize($data, $class);
    }
}
