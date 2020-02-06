<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;

/**
 */
interface CommentRepositoryInterface
{
    /**
     * @param CommentId $id
     *
     * @return Comment
     */
    public function load(CommentId $id): ?AbstractAggregateRoot;

    /**
     * @param Comment $object
     */
    public function save(Comment $object): void;

    /**
     * @param CommentId $id
     *
     * @return bool
     */
    public function exists(CommentId $id): bool;

    /**
     * @param Comment $object
     */
    public function delete(Comment $object): void;
}
