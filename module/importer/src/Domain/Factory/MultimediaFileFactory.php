<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Factory;

use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Symfony\Component\HttpFoundation\File\File;
use League\Flysystem\FilesystemInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Ramsey\Uuid\Uuid;
use Ergonode\Core\Infrastructure\Service\DownloaderInterface;

class MultimediaFileFactory
{
    private FilesystemInterface $multimediaStorage;

    private HashCalculationServiceInterface $hashService;

    private DownloaderInterface $downloader;

    public function __construct(
        FilesystemInterface $multimediaStorage,
        HashCalculationServiceInterface $hashService,
        DownloaderInterface $downloader
    ) {
        $this->multimediaStorage = $multimediaStorage;
        $this->hashService = $hashService;
        $this->downloader = $downloader;
    }

    public function create(string $name, string $url): AbstractMultimedia
    {
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        $tmpFile = tempnam(sys_get_temp_dir(), Uuid::uuid4()->toString());

        $content = $this->downloader->download($url);
        file_put_contents($tmpFile, $content);

        $file = new File($tmpFile);

        $id = MultimediaId::generate();
        $hash = $this->hashService->calculateHash($file);

        $filename = sprintf('%s.%s', $id->getValue(), $extension);
        $this->multimediaStorage->write($filename, $content);
        $size = $this->multimediaStorage->getSize($filename);
        $mime = $this->multimediaStorage->getMimetype($filename);

        unlink($tmpFile);

        return new Multimedia(
            $id,
            $name,
            $extension,
            $size,
            $hash,
            $mime,
        );
    }
}
