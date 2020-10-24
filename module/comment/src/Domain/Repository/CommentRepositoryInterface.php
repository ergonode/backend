<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;

interface CommentRepositoryInterface
{
    /**
     * @return Comment
     */
    public function load(CommentId $id): ?AbstractAggregateRoot;

    public function save(Comment $object): void;

    public function exists(CommentId $id): bool;

    public function delete(Comment $object): void;
}
