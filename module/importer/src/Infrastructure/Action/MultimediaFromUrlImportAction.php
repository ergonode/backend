<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Core\Infrastructure\Service\DownloaderInterface;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File;

class MultimediaFromUrlImportAction
{
    private MultimediaRepositoryInterface $repository;

    private DownloaderInterface $downloader;

    private HashCalculationServiceInterface $hashService;

    private FilesystemInterface $multimediaStorage;

    private MultimediaQueryInterface $multimediaQuery;

    public function __construct(
        MultimediaRepositoryInterface $repository,
        DownloaderInterface $downloader,
        HashCalculationServiceInterface $hashService,
        FilesystemInterface $multimediaStorage,
        MultimediaQueryInterface $multimediaQuery
    ) {
        $this->repository = $repository;
        $this->downloader = $downloader;
        $this->hashService = $hashService;
        $this->multimediaStorage = $multimediaStorage;
        $this->multimediaQuery = $multimediaQuery;
    }

    /**
     * @throws FileExistsException
     * @throws FileNotFoundException
     */
    public function action(ImportId $importId, string $url, string $filename): void
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);

        $id = $this->multimediaQuery->findIdByFilename($name);

        if (!$id) {
            try {
                $tmpFile = tempnam(sys_get_temp_dir(), $importId->getValue());

                $content = $this->downloader->download($url);
                file_put_contents($tmpFile, $content);
                $file = new File($tmpFile);

                $hash = $this->hashService->calculateHash($file);
                $filename = sprintf('%s.%s', $hash->getValue(), $extension);
                if (!$this->multimediaStorage->has($filename)) {
                    $this->multimediaStorage->write($filename, $content);
                }

                $size = $this->multimediaStorage->getSize($filename);
                $mime = $this->multimediaStorage->getMimetype($filename);

                $multimedia = new Multimedia(
                    MultimediaId::generate(),
                    $name,
                    $extension,
                    $size,
                    $hash,
                    $mime,
                );

                $this->repository->save($multimedia);
                unlink($tmpFile);
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
    }
}
