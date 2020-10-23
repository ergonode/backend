<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use JMS\Serializer\SerializerInterface;

class DbalSourceMapper
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return array
     */
    public function map(AbstractSource $source): array
    {
        return [
            'id' => $source->getId()->getValue(),
            'configuration' => $this->serializer->serialize($source, 'json'),
            'name' => $source->getName(),
            'class' => \get_class($source),
            'type' => $source->getType(),
        ];
    }
}
