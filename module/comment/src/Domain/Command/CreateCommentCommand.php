<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;

/**
 */
class CreateCommentCommand implements DomainCommandInterface
{
    /**
     * @var CommentId $id
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CommentId")
     */
    private CommentId $id;

    /**
     * @var UserId $authorId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $authorId;

    /**
     * @var Uuid
     *
     * @JMS\Type("Ramsey\Uuid\Uuid")
     */
    private Uuid $objectId;

    /**
     * @var string $content
     *
     * @JMS\Type("string")
     */
    private string $content;

    /**
     * @param UserId $authorId
     * @param Uuid   $uuid
     * @param string $content
     *
     * @throws \Exception
     */
    public function __construct(UserId $authorId, Uuid $uuid, string $content)
    {
        $this->id = CommentId::generate();
        $this->authorId = $authorId;
        $this->objectId = $uuid;
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
     * @return UserId
     */
    public function getAuthorId(): UserId
    {
        return $this->authorId;
    }

    /**
     * @return Uuid
     */
    public function getObjectId(): Uuid
    {
        return $this->objectId;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
