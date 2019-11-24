<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Domain\Event;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Comment\Domain\Entity\CommentId;
use Ergonode\Comment\Domain\Event\CommentCreatedEvent;
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
        $this->assertSame($userId, $command->getAuthorId());
        $this->assertSame($objectId, $command->getObjectId());
        $this->assertSame($content, $command->getContent());
        $this->assertSame($commentId, $command->getId());
        $this->assertSame($createAt, $command->getCreatedAt());
    }
}
