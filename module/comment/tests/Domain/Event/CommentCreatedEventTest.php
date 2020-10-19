<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Comment\Domain\Event\CommentCreatedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class CommentCreatedEventTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        /** @var CommentId $commentId */
        $commentId = $this->createMock(CommentId::class);
        /** @var UserId $userId */
        $userId = $this->createMock(UserId::class);
        /** @var Uuid $objectId */
        $objectId = $this->createMock(Uuid::class);
        $content = 'Any content';
        /** @var \DateTime $createAt */
        $createAt = $this->createMock(\DateTime::class);

        $command = new CommentCreatedEvent($commentId, $userId, $objectId, $content, $createAt);
        self::assertSame($userId, $command->getAuthorId());
        self::assertSame($objectId, $command->getObjectId());
        self::assertSame($content, $command->getContent());
        self::assertSame($commentId, $command->getAggregateId());
        self::assertSame($createAt, $command->getCreatedAt());
    }
}
