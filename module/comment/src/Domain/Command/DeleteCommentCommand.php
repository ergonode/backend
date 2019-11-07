<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Command;

use Ergonode\Comment\Domain\Entity\CommentId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DeleteCommentCommand
{
    /**
     * @var CommentId $id
     *
     * @JMS\Type("Ergonode\Comment\Domain\Entity\CommentId")
     */
    private $id;

    /**
     * DeleteCommentCommand constructor.
     *
     * @param CommentId $id
     */
    public function __construct(CommentId $id)
    {
        $this->id = $id;
    }

    /**
     * @return CommentId
     */
    public function getId(): CommentId
    {
        return $this->id;
    }
}
