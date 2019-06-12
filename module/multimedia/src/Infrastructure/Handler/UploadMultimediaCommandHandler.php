<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Command\UploadMultimediaCommand;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Ergonode\Multimedia\Infrastructure\Service\Upload\MultimediaUploadService;

/**
 */
class UploadMultimediaCommandHandler
{
    /**
     * @var MultimediaUploadService;
     */
    private $uploadService;

    /**
     * @var HashCalculationServiceInterface
     */
    private $hashService;

    /**
     * @var MultimediaRepositoryInterface
     */
    private $repository;

    /**
     * @param MultimediaUploadService         $uploadService
     * @param HashCalculationServiceInterface $hashService
     * @param MultimediaRepositoryInterface   $repository
     */
    public function __construct(
        MultimediaUploadService $uploadService,
        HashCalculationServiceInterface $hashService,
        MultimediaRepositoryInterface $repository
    ) {
        $this->uploadService = $uploadService;
        $this->hashService = $hashService;
        $this->repository = $repository;
    }

    /**
     * @param UploadMultimediaCommand $command
     */
    public function __invoke(UploadMultimediaCommand $command)
    {
        $id = $command->getId();
        $uploadedFile = $command->getFile();
        $file = $this->uploadService->upload($id, $uploadedFile);
        $crc = $this->hashService->calculateHash($file);

        $multimedia = Multimedia::createFromFile($id, $uploadedFile->getClientOriginalName(), $file, $crc);

        $this->repository->save($multimedia);
    }
}
