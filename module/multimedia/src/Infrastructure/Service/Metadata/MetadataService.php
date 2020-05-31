<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service\Metadata;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaFileProviderInterface;

/**
 */
class MetadataService
{
    /**
     * @var MultimediaFileProviderInterface
     */
    private MultimediaFileProviderInterface $provider;

    /**
     * @var MetadataReader
     */
    private MetadataReader $reader;

    /**
     * @param MultimediaFileProviderInterface $provider
     * @param MetadataReader                  $reader
     */
    public function __construct(
        MultimediaFileProviderInterface $provider,
        MetadataReader $reader
    ) {
        $this->provider = $provider;
        $this->reader = $reader;
    }

    /**
     * @param Multimedia $multimedia
     *
     * @return array
     */
    public function getMetadata(Multimedia $multimedia): array
    {
        $file = $this->provider->getFile($multimedia->getFileName());

        return $this->reader->read($file);
    }
}
