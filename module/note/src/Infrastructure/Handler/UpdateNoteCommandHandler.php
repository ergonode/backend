<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Infrastructure\Handler;

use Ergonode\Note\Domain\Command\UpdateNoteCommand;
use Ergonode\Note\Domain\Repository\NoteRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class UpdateNoteCommandHandler
{
    /**
     * @var NoteRepositoryInterface $repository
     */
    private $repository;

    /**
     * @param NoteRepositoryInterface $repository
     */
    public function __construct(NoteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateNoteCommand $command
     */
    public function __invoke(UpdateNoteCommand $command): void
    {
        $note = $this->repository->load($command->getId());
        Assert::notNull($note);
        $note->changeContent($command->getContent());
        $this->repository->save($note);
    }
}
