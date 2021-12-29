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
        $info = [];
        $resource = $this->multimediaStorage->readStream($multimedia->getFileName());

        $info['size'] = number_format($multimedia->getSize() / 1024, 2).' KB';

        if ($multimedia->getMime()) {
            $info['type'] = ucfirst(substr($multimedia->getMime(), 0, strpos($multimedia->getMime(), '/')));
        }

        $timestamp = $this->multimediaStorage->getTimestamp($multimedia->getFileName());
        if ($timestamp) {
            $date = new \DateTime();
            $date->setTimestamp($timestamp);
            $info['created at'] = $date->format('Y-m-d H:i');
        }

        $metadata = $this->reader->read($resource);

        return array_merge($info, $metadata);
    }
}
