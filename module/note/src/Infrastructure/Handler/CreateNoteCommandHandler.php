<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Infrastructure\Handler;

use Ergonode\Note\Domain\Command\CreateNoteCommand;
use Ergonode\Note\Domain\Factory\NoteFactoryInterface;
use Ergonode\Note\Domain\Repository\NoteRepositoryInterface;

/**
 */
class CreateNoteCommandHandler
{
    /**
     * @var NoteRepositoryInterface $repository
     */
    private $repository;

    /**
     * @var NoteFactoryInterface $factory
     */
    private $factory;

    /**
     * @param NoteRepositoryInterface $repository
     * @param NoteFactoryInterface    $factory
     */
    public function __construct(NoteRepositoryInterface $repository, NoteFactoryInterface $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param CreateNoteCommand $command
     */
    public function __invoke(CreateNoteCommand $command): void
    {
        $entity = $this->factory->create($command->getId(), $command->getAuthorId(), $command->getObjectId(), $command->getContent());
        $this->repository->save($entity);
    }
}
