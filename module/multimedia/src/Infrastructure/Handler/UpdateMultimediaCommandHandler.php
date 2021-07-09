<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Domain\Command\UpdateMultimediaCommand;
use Webmozart\Assert\Assert;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;

class UpdateMultimediaCommandHandler
{
    private MultimediaRepositoryInterface $repository;

    public function __construct(
        MultimediaRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateMultimediaCommand $command): void
    {
        $multimedia = $this->repository->load($command->getId());
        Assert::isInstanceOf($multimedia, AbstractMultimedia::class);

        $multimedia->changeAlt($command->getAlt());
        $multimedia->changeName($command->getName());
        $multimedia->changeTitle($command->getTitle());

        $this->repository->save($multimedia);
    }
}
