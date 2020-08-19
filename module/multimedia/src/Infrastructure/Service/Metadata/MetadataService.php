<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service\Metadata;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use League\Flysystem\FilesystemInterface;

/**
 */
class MetadataService
{
    /**
     * @var MetadataReader
     */
    private MetadataReader $reader;

    /**
     * @var FilesystemInterface
     */
    private FilesystemInterface $multimediaStorage;

    /**
     * @param MetadataReader      $reader
     * @param FilesystemInterface $multimediaStorage
     */
    public function __construct(MetadataReader $reader, FilesystemInterface $multimediaStorage)
    {
        $this->reader = $reader;
        $this->multimediaStorage = $multimediaStorage;
    }

    /**
     * @param Multimedia $multimedia
     *
     * @return array
     */
    public function getMetadata(Multimedia $multimedia): array
    {
        $resource = $this->multimediaStorage->readStream($multimedia->getFileName());

        return $this->reader->read($resource);
    }
}
