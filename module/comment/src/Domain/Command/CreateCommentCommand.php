<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use Ramsey\Uuid\Uuid;

class CreateCommentCommand implements CommentCommandInterface
{
    private CommentId $id;

    private UserId $authorId;

    private Uuid $objectId;

    private string $content;

    /**
     * @throws \Exception
     */
    public function __construct(UserId $authorId, Uuid $uuid, string $content)
    {
        $this->id = CommentId::generate();
        $this->authorId = $authorId;
        $this->objectId = $uuid;
        $this->content = $content;
    }

    public function getId(): CommentId
    {
        return $this->id;
    }

    public function getAuthorId(): UserId
    {
        return $this->authorId;
    }

    public function getObjectId(): Uuid
    {
        return $this->objectId;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
