<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\CommentId;

class UpdateCommentCommand implements CommentCommandInterface
{
    private CommentId $id;

    private string $content;

    public function __construct(CommentId $id, string $content)
    {
        $this->id = $id;
        $this->content = $content;
    }

    public function getId(): CommentId
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
