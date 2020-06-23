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
use Ergonode\Multimedia\Infrastructure\Storage\ResourceStorageInterface;

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
     * @var ResourceStorageInterface
     */
    private ResourceStorageInterface $multimediaStorage;

    /**
     * @param HashCalculationServiceInterface $hashService
     * @param MultimediaRepositoryInterface   $repository
     * @param ResourceStorageInterface        $multimediaStorage
     */
    public function __construct(
        HashCalculationServiceInterface $hashService,
        MultimediaRepositoryInterface $repository,
        ResourceStorageInterface $multimediaStorage
    ) {
        $this->hashService = $hashService;
        $this->repository = $repository;
        $this->multimediaStorage = $multimediaStorage;
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
        $file = $command->getFile();
        $hash = $this->hashService->calculateHash($file);
        $originalName = $file->getFilename();

        $extension = $file->getExtension();
        if (empty($extension) || '.' === $extension) {
            $extension = $file->guessExtension();
        }

        $filename = sprintf('%s.%s', $hash->getValue(), $extension);

        if (!$this->multimediaStorage->has($filename)) {
            $content = file_get_contents($file->getRealPath());
            $this->multimediaStorage->write($filename, $content);
        }

        $info = $this->multimediaStorage->info($filename);

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
