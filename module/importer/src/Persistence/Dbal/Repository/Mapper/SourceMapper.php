<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository\Mapper;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use JMS\Serializer\SerializerInterface;

/**
 */
class SourceMapper
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
     * @param AbstractSource $source
     *
     * @return array
     */
    public function map(AbstractSource $source): array
    {
        return [
            'id' => $source->getId()->getValue(),
            'configuration' => $this->serializer->serialize($source, 'json'),
            'name' => $source->getName(),
            'type' => \get_class($source),
        ];
    }
}
