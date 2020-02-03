<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Command;

use Ergonode\Comment\Domain\Entity\CommentId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DeleteCommentCommand implements DomainCommandInterface
{
    /**
     * @var CommentId $id
     *
     * @JMS\Type("Ergonode\Comment\Domain\Entity\CommentId")
     */
    private CommentId $id;

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
