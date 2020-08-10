<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Ergonode\Multimedia\Infrastructure\Storage\MultimediaStorageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 */
class AddMultimediaCommandHandler
{
    /**
     * @var HashCalculationServiceInterface
     */
    private HashCalculationServiceInterface $hashService;

    /**
     * @var MultimediaRepositoryInterface
     */
    private MultimediaRepositoryInterface $repository;

    /**
     * @var MultimediaStorageInterface
     */
    private MultimediaStorageInterface $storage;

    /**
     * @param HashCalculationServiceInterface $hashService
     * @param MultimediaRepositoryInterface   $repository
     * @param MultimediaStorageInterface      $storage
     */
    public function __construct(
        HashCalculationServiceInterface $hashService,
        MultimediaRepositoryInterface $repository,
        MultimediaStorageInterface $storage
    ) {
        $this->hashService = $hashService;
        $this->repository = $repository;
        $this->storage = $storage;
    }

    /**
     * @param AddMultimediaCommand $command
     *
     * @return mixed
     *
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

        if (!$this->storage->has($filename)) {
            $content = file_get_contents($file->getRealPath());
            $this->storage->write($filename, $content);
        }

        $info = $this->storage->info($filename);

        $multimedia = new Multimedia(
            $id,
            $originalName,
            $extension,
            $info['size'],
            $hash,
            $info['mime']
        );

        $this->repository->save($multimedia);
    }
}
