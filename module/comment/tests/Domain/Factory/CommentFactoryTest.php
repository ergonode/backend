<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Domain\Factory;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Comment\Domain\Factory\CommentFactory;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class CommentFactoryTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testFactoryCreation(): void
    {
        /** @var CommentId $commentId */
        $commentId = $this->createMock(CommentId::class);
        /** @var UserId $userId */
        $userId = $this->createMock(UserId::class);
        /** @var Uuid $objectId */
        $objectId = $this->createMock(Uuid::class);
        $content = 'Any content';

        $factory = new CommentFactory();

        $comment = $factory->create($commentId, $userId, $objectId, $content);
        self::assertSame($commentId, $comment->getId());
        self::assertSame($userId, $comment->getAuthorId());
        self::assertSame($objectId, $comment->getObjectId());
        self::assertSame($content, $comment->getContent());
    }
}
