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

class UpdateCommentCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CommentId")
     */
    private CommentId $id;

    /**
     * @JMS\Type("string")
     */
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
