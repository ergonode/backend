<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Tests\Domain\Event;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Note\Domain\Entity\NoteId;
use Ergonode\Note\Domain\Event\NoteCreatedEvent;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class NoteCreatedEventTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        /** @var NoteId $noteId */
        $noteId = $this->createMock(NoteId::class);
        /** @var UserId $userId */
        $userId = $this->createMock(UserId::class);
        /** @var Uuid $objectId */
        $objectId = $this->createMock(Uuid::class);
        $content = 'Any content';
        /** @var \DateTime $createAt */
        $createAt = $this->createMock(\DateTime::class);

        $command = new NoteCreatedEvent($noteId, $userId, $objectId, $content, $createAt);
        $this->assertSame($userId, $command->getAuthorId());
        $this->assertSame($objectId, $command->getObjectId());
        $this->assertSame($content, $command->getContent());
        $this->assertSame($noteId, $command->getId());
        $this->assertSame($createAt, $command->getCreatedAt());
    }
}
