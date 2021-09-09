<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Infrastructure\Handler;

use Ergonode\Comment\Domain\Command\CreateCommentCommand;
use Ergonode\Comment\Domain\Factory\CommentFactoryInterface;
use Ergonode\Comment\Domain\Repository\CommentRepositoryInterface;

class CreateCommentCommandHandler
{
    private CommentRepositoryInterface $repository;

    private CommentFactoryInterface $factory;

    public function __construct(CommentRepositoryInterface $repository, CommentFactoryInterface $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    public function __invoke(CreateCommentCommand $command): void
    {
        $entity = $this
            ->factory
            ->create(
                $command->getId(),
                $command->getAuthorId(),
                $command->getObjectId(),
                $command->getContent()
            );
        $this->repository->save($entity);
    }
}
