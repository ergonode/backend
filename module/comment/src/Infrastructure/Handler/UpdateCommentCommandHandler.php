<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Infrastructure\Handler;

use Ergonode\Comment\Domain\Command\UpdateCommentCommand;
use Ergonode\Comment\Domain\Repository\CommentRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class UpdateCommentCommandHandler
{
    /**
     * @var CommentRepositoryInterface $repository
     */
    private $repository;

    /**
     * @param CommentRepositoryInterface $repository
     */
    public function __construct(CommentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateCommentCommand $command
     */
    public function __invoke(UpdateCommentCommand $command): void
    {
        $comment = $this->repository->load($command->getId());
        Assert::notNull($comment);
        $comment->changeContent($command->getContent());
        $this->repository->save($comment);
    }
}
