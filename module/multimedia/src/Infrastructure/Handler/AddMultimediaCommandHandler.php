<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddMultimediaCommandHandler
{
    private HashCalculationServiceInterface $hashService;

    private MultimediaRepositoryInterface $repository;

    private FilesystemInterface $multimediaStorage;

    public function __construct(
        HashCalculationServiceInterface $hashService,
        MultimediaRepositoryInterface $repository,
        FilesystemInterface $multimediaStorage
    ) {
        $this->hashService = $hashService;
        $this->repository = $repository;
        $this->multimediaStorage = $multimediaStorage;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(AddMultimediaCommand $command): void
    {
        $id = $command->getId();
        /** @var UploadedFile $file */
        $file = $command->getFile();
        $hash = $this->hashService->calculateHash($file);
        $originalName = $file->getClientOriginalName();

        $extension = $file->getClientOriginalExtension();
        if (empty($extension) || '.' === $extension) {
            $extension = $file->guessExtension();
        }

        $filename = sprintf('%s.%s', $hash->getValue(), $extension);

        if (!$this->multimediaStorage->has($filename)) {
            $content = file_get_contents($file->getRealPath());
            $this->multimediaStorage->write($filename, $content);
        }

        $multimedia = new Multimedia(
            $id,
            $originalName,
            $extension,
            $this->multimediaStorage->getSize($filename),
            $hash,
            $this->multimediaStorage->getMimetype($filename)
        );

        $this->repository->save($multimedia);
    }
}
