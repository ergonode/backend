<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service\Metadata;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Infrastructure\Storage\ResourceStorageInterface;

/**
 */
class MetadataService
{
    /**
     * @var MetadataReader
     */
    private MetadataReader $reader;

    /**
     * @var ResourceStorageInterface
     */
    private ResourceStorageInterface $storage;

    /**
     * @param MetadataReader           $reader
     * @param ResourceStorageInterface $storage
     */
    public function __construct(MetadataReader $reader, ResourceStorageInterface $storage)
    {
        $this->reader = $reader;
        $this->storage = $storage;
    }

    /**
     * @param Multimedia $multimedia
     *
     * @return array
     */
    public function getMetadata(Multimedia $multimedia): array
    {
        $resource = $this->storage->readStream($multimedia->getFileName());

        return $this->reader->read($resource);
    }
}
