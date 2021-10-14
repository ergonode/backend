<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Core\Infrastructure\Service\DownloaderInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
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
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;

class MultimediaFromUrlImportAction
{
    private MultimediaRepositoryInterface $repository;

    private DownloaderInterface $downloader;

    private HashCalculationServiceInterface $hashService;

    private FilesystemInterface $multimediaStorage;

    private MultimediaQueryInterface $multimediaQuery;

    private MultimediaExtensionProvider $provider;

    public function __construct(
        MultimediaRepositoryInterface $repository,
        DownloaderInterface $downloader,
        HashCalculationServiceInterface $hashService,
        FilesystemInterface $multimediaStorage,
        MultimediaQueryInterface $multimediaQuery,
        MultimediaExtensionProvider $provider
    ) {
        $this->repository = $repository;
        $this->downloader = $downloader;
        $this->hashService = $hashService;
        $this->multimediaStorage = $multimediaStorage;
        $this->multimediaQuery = $multimediaQuery;
        $this->provider = $provider;
    }

    /**
     * @throws FileExistsException
     * @throws FileNotFoundException
     */
    public function action(
        ImportId $importId,
        string $url,
        string $name,
        ?TranslatableString $alt = null
    ): MultimediaId {
        $extension = pathinfo($name, PATHINFO_EXTENSION);

        if ($this->multimediaQuery->findIdByFilename($name)) {
            throw new ImportException('Multimedia with {name} already exists in the system', ['{name}' => $name]);
        }

        if (!in_array($extension, $this->provider->dictionary(), true)) {
            throw new ImportException('Multimedia type {type} is not allowed ', ['{type}' => $extension]);
        }

        $tmpFile = tempnam(sys_get_temp_dir(), $importId->getValue());

        $content = $this->downloader->download($url);
        file_put_contents($tmpFile, $content);
        $file = new File($tmpFile);

        $hash = $this->hashService->calculateHash($file);
        $id = MultimediaId::generate();
        $filename = sprintf('%s.%s', $id->getValue(), $extension);
        $this->multimediaStorage->write($filename, $content);
        $size = $this->multimediaStorage->getSize($filename);
        $mime = $this->multimediaStorage->getMimetype($filename);

        $multimedia = new Multimedia(
            $id,
            $name,
            $extension,
            $size,
            $hash,
            $mime,
        );

        if ($alt) {
            $multimedia->changeAlt($alt);
        }

        $this->repository->save($multimedia);
        unlink($tmpFile);

        return $id;
    }
}
