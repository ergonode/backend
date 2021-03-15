<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service\Metadata;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use League\Flysystem\FilesystemInterface;

class MetadataService
{
    private MetadataReader $reader;

    private FilesystemInterface $multimediaStorage;

    public function __construct(MetadataReader $reader, FilesystemInterface $multimediaStorage)
    {
        $this->reader = $reader;
        $this->multimediaStorage = $multimediaStorage;
    }

    /**
     * @return array
     */
    public function getMetadata(Multimedia $multimedia): array
    {
        $resource = $this->multimediaStorage->readStream($multimedia->getFileName());

        return $this->reader->read($resource);
    }
}
