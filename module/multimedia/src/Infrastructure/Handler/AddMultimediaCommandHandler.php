<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Ergonode\Multimedia\Infrastructure\Service\Upload\MultimediaUploadService;
use Ergonode\Multimedia\Domain\Factory\MultimediaFactory;

/**
 */
class AddMultimediaCommandHandler
{
    /**
     * @var MultimediaUploadService;
     */
    private $uploadService;

    /**
     * @var MultimediaQueryInterface
     */
    private $query;

    /**
     * @var HashCalculationServiceInterface
     */
    private $hashService;

    /**
     * @var MultimediaRepositoryInterface
     */
    private $repository;

    /**
     * @var MultimediaFactory
     */
    private $factory;

    /**
     * @param MultimediaUploadService         $uploadService
     * @param MultimediaQueryInterface        $query
     * @param HashCalculationServiceInterface $hashService
     * @param MultimediaRepositoryInterface   $repository
     * @param MultimediaFactory               $factory
     */
    public function __construct(
        MultimediaUploadService $uploadService,
        MultimediaQueryInterface $query,
        HashCalculationServiceInterface $hashService,
        MultimediaRepositoryInterface $repository,
        MultimediaFactory $factory
    ) {
        $this->uploadService = $uploadService;
        $this->query = $query;
        $this->hashService = $hashService;
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param AddMultimediaCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(AddMultimediaCommand $command)
    {
        $id = $command->getId();
        $file = $command->getFile();
        $hash = $this->hashService->calculateHash($file);
        if (!$this->query->fileExists($hash)) {
            $originalName = $file->getFilename();
            $file = $this->uploadService->upload($id, $file);

            $multimedia = $this->factory->create($id, $originalName, $file, $hash);

            $this->repository->save($multimedia);
        }
    }
}
