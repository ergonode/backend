<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Domain\Command;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Comment\Domain\Entity\CommentId;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Uuid;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class CreateCommentCommand implements DomainCommandInterface
{
    /**
     * @var CommentId $id
     *
     * @JMS\Type("Ergonode\Comment\Domain\Entity\CommentId")
     */
    private $id;

    /**
     * @var UserId $authorId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\UserId")
     */
    private $authorId;

    /**
     * @var Uuid
     *
     * @JMS\Type("Ramsey\Uuid\Uuid")
     */
    private $objectId;

    /**
     * @var string $content
     *
     * @JMS\Type("string")
     */
    private $content;

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
