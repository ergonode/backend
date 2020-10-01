<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DeleteCommentCommand implements DomainCommandInterface
{
    /**
     * @var CommentId $id
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CommentId")
     */
    private CommentId $id;

    /**
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
