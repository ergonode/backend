<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Handler;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Domain\Command\UpdateMultimediaCommand;
use http\Exception\UnexpectedValueException;

class UpdateMultimediaCommandHandler
{
    private MultimediaRepositoryInterface $repository;

    private MultimediaQueryInterface $query;

    public function __construct(
        MultimediaRepositoryInterface $repository,
        MultimediaQueryInterface $query
    ) {
        $this->repository = $repository;
        $this->query = $query;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateMultimediaCommand $command): void
    {
        if ($this->query->findIdByFilename($command->getName())) {
            throw new UnexpectedValueException(sprintf('Multimedia name %s already exists.', $command->getName()));
        }
        /** @var Multimedia $multimedia */
        $multimedia = $this->repository->load($command->getId());
        $multimedia->changeAlt($command->getAlt());
        $multimedia->changeName($command->getName());
        $this->repository->save($multimedia);
    }
}
