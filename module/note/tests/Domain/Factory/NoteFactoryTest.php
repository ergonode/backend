<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Tests\Domain\Factory;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Note\Domain\Entity\NoteId;
use Ergonode\Note\Domain\Factory\NoteFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class NoteFactoryTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testFactoryCreation(): void
    {
        /** @var NoteId $noteId */
        $noteId = $this->createMock(NoteId::class);
        /** @var UserId $userId */
        $userId = $this->createMock(UserId::class);
        /** @var Uuid $objectId */
        $objectId = $this->createMock(Uuid::class);
        $content = 'Any content';

        $factory = new NoteFactory();

        $note = $factory->create($noteId, $userId, $objectId, $content);
        $this->assertSame($noteId, $note->getId());
        $this->assertSame($userId, $note->getAuthorId());
        $this->assertSame($objectId, $note->getObjectId());
        $this->assertSame($content, $note->getContent());
    }
}
