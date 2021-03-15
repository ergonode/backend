<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Domain\Factory;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use Ramsey\Uuid\Uuid;

class CommentFactory implements CommentFactoryInterface
{
    /**
     * @throws \Exception
     */
    public function create(CommentId $id, UserId $authorId, Uuid $objectId, string $content): Comment
    {
        return new Comment(
            $id,
            $objectId,
            $authorId,
            $content
        );
    }
}
