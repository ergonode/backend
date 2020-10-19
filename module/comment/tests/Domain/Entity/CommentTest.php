<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class CommentTest extends TestCase
{
    /**
     * @var CommentId|MockObject
     */
    private $id;

    /**
     * @var Uuid|MockObject
     */
    private $uuid;

    /**
     * @var UserId|MockObject
     */
    private $userId;

    /**
     * @var string
     */
    private string $content;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(CommentId::class);
        $this->uuid = $this->createMock(Uuid::class);
        $this->userId = $this->createMock(UserId::class);
        $this->content = 'Any content';
    }

    /**
     * @throws \Exception
     */
    public function testCommentCreation(): void
    {
        $comment = new Comment($this->id, $this->uuid, $this->userId, $this->content);
        self::assertSame($this->id, $comment->getId());
        self::assertSame($this->userId, $comment->getAuthorId());
        self::assertSame($this->uuid, $comment->getObjectId());
        self::assertSame($this->content, $comment->getContent());
        self::assertEmpty($comment->getEditedAt());
        self::assertNotEmpty($comment->getCreatedAt());
    }

    /**
     * @throws \Exception
     */
    public function testContendEditCreation(): void
    {
        $newContent = 'new Content';
        $comment = new Comment($this->id, $this->uuid, $this->userId, $this->content);
        $comment->changeContent($newContent);
        self::assertSame($newContent, $comment->getContent());
        self::assertNotEmpty($comment->getEditedAt());
    }
}
