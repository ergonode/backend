<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Ergonode\Multimedia\Infrastructure\Storage\MultimediaStorageInterface;
use Ergonode\Multimedia\Domain\Command\UpdateMultimediaCommand;

/**
 */
class UpdateMultimediaCommandHandler
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
     * @param UpdateMultimediaCommand $command
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function __invoke(UpdateMultimediaCommand $command): void
    {
        /** @var Multimedia $multimedia */
        $multimedia = $this->repository->load($command->getId());
        $multimedia->changeAlt($command->getAlt());
        $multimedia->changeName($command->getName());
        $this->repository->save($multimedia);
    }
}
