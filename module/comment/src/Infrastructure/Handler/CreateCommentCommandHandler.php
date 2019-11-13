<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Infrastructure\Handler;

use Ergonode\Comment\Domain\Command\CreateCommentCommand;
use Ergonode\Comment\Domain\Factory\CommentFactoryInterface;
use Ergonode\Comment\Domain\Repository\CommentRepositoryInterface;

/**
 */
class CreateCommentCommandHandler
{
    /**
     * @var CommentRepositoryInterface $repository
     */
    private $repository;

    /**
     * @var CommentFactoryInterface $factory
     */
    private $factory;

    /**
     * @param CommentRepositoryInterface $repository
     * @param CommentFactoryInterface    $factory
     */
    public function __construct(CommentRepositoryInterface $repository, CommentFactoryInterface $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param CreateCommentCommand $command
     */
    public function __invoke(CreateCommentCommand $command): void
    {
        $entity = $this->factory->create($command->getId(), $command->getAuthorId(), $command->getObjectId(), $command->getContent());
        $this->repository->save($entity);
    }
}
