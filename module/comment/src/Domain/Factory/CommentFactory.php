<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Factory;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\Comment\Domain\Entity\CommentId;
use Ramsey\Uuid\Uuid;

/**
 */
class CommentFactory implements CommentFactoryInterface
{
    /**
     * @param CommentId $id
     * @param UserId    $authorId
     * @param Uuid      $objectId
     * @param string    $content
     *
     * @return Comment
     *
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
