<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service\Thumbnail;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\FileExistsException;

class ThumbnailGenerator
{
    private FilesystemInterface $multimediaStorage;

    private FilesystemInterface $thumbnailStorage;

    private TempFileStorage $temp;

    private ThumbnailGenerationStrategyProvider $provider;

    public function __construct(
        FilesystemInterface $multimediaStorage,
        FilesystemInterface $thumbnailStorage,
        TempFileStorage $temp,
        ThumbnailGenerationStrategyProvider $provider
    ) {
        $this->multimediaStorage = $multimediaStorage;
        $this->thumbnailStorage = $thumbnailStorage;
        $this->temp = $temp;
        $this->provider = $provider;
    }

    /**
     * @throws FileExistsException
     * @throws \ImagickException
     */
    public function generate(Multimedia $multimedia, string $type): void
    {
        $strategy = $this->provider->provide($type);
        $content = $this->multimediaStorage->read($multimedia->getFileName());
        $this->temp->create($multimedia->getFileName());
        $this->temp->append([$content]);
        $this->temp->close();

        $filename = $this->temp->getDirectory().'/'.$multimedia->getFileName();
        $imagick = new \Imagick(realpath($filename));
        $imagick = $strategy->generate($imagick);
        $this->temp->clean($filename);

        $newFilename = sprintf('%s/%s.png', $this->temp->getDirectory(), $multimedia->getId()->getValue());
        $imagick->writeImage($newFilename);
        $imagick->destroy();

        $handler = fopen($newFilename, 'rb');
        $this->thumbnailStorage->writeStream(sprintf('%s/%s', $type, basename($newFilename)), $handler);
        $this->temp->clean($newFilename);
    }
}
