<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Domain\Command\UpdateMultimediaCommand;

class UpdateMultimediaCommandHandler
{
    /**
     * @var MultimediaRepositoryInterface
     */
    private MultimediaRepositoryInterface $repository;

    /**
     * @param MultimediaRepositoryInterface $repository
     */
    public function __construct(
        MultimediaRepositoryInterface $repository
    ) {
        $this->repository = $repository;
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
