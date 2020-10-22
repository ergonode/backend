<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Factory;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use Ramsey\Uuid\Uuid;

interface CommentFactoryInterface
{
    /**
     * @param CommentId $id
     * @param UserId    $authorId
     * @param Uuid      $objectId
     * @param string    $content
     *
     * @return Comment
     */
    public function create(CommentId $id, UserId $authorId, Uuid $objectId, string $content): Comment;
}
