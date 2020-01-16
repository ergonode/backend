<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Infrastructure\Handler;

use Ergonode\Comment\Domain\Command\DeleteCommentCommand;
use Ergonode\Comment\Domain\Repository\CommentRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteCommentCommandHandler
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
     * @param DeleteCommentCommand $command
     */
    public function __invoke(DeleteCommentCommand $command): void
    {
        $comment = $this->repository->load($command->getId());
        Assert::notNull($comment);
        $this->repository->delete($comment);
    }
}
