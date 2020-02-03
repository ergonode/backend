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
class UpdateCommentCommand implements DomainCommandInterface
{
    /**
     * @var CommentId $id
     *
     * @JMS\Type("Ergonode\Comment\Domain\Entity\CommentId")
     */
    private CommentId $id;

    /**
     * @var string $content
     *
     * @JMS\Type("string")
     */
    private string $content;

    /**
     * @param CommentId $id
     * @param string    $content
     */
    public function __construct(CommentId $id, string $content)
    {
        $this->id = $id;
        $this->content = $content;
    }

    /**
     * @return CommentId
     */
    public function getId(): CommentId
    {
        return $this->id;
    }

        /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
