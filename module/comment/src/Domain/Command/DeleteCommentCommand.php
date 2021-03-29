<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\CommentId;

class DeleteCommentCommand implements CommentCommandInterface
{
    private CommentId $id;

    public function __construct(CommentId $id)
    {
        $this->id = $id;
    }

    public function getId(): CommentId
    {
        return $this->id;
    }
}
