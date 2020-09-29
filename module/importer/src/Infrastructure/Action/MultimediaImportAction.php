<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Symfony\Component\HttpFoundation\File\File;
use Ergonode\Core\Infrastructure\Service\DownloaderInterface;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use League\Flysystem\FilesystemInterface;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FileExistsException;

/**
 */
class MultimediaImportAction
{
    /**
     * @var MultimediaRepositoryInterface
     */
    private MultimediaRepositoryInterface $repository;

    /**
     * @var DownloaderInterface
     */
    private DownloaderInterface $downloader;

    /**
     * @var HashCalculationServiceInterface
     */
    private HashCalculationServiceInterface $hashService;

    /**
     * @var FilesystemInterface
     */
    private FilesystemInterface $multimediaStorage;

    /**
     * @var MultimediaQueryInterface
     */
    private MultimediaQueryInterface $multimediaQuery;

    /**
     * @param MultimediaRepositoryInterface   $repository
     * @param DownloaderInterface             $downloader
     * @param HashCalculationServiceInterface $hashService
     * @param FilesystemInterface             $multimediaStorage
     * @param MultimediaQueryInterface        $multimediaQuery
     */
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
     * @param ImportId $importId
     * @param string   $url
     * @param string   $filename
     *
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
