<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Tests\Domain\Entity;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Note\Domain\Entity\Note;
use Ergonode\Note\Domain\Entity\NoteId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class NoteTest extends TestCase
{
    /**
     * @var NoteId|MockObject
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
    private $content;

    /**
     */
    protected function setUp()
    {
        $this->id = $this->createMock(NoteId::class);
        $this->uuid = $this->createMock(Uuid::class);
        $this->userId = $this->createMock(UserId::class);
        $this->content = 'Any content';
    }

    /**
     * @throws \Exception
     */
    public function testNoteCreation(): void
    {
        $note = new Note($this->id, $this->uuid, $this->userId, $this->content);
        $this->assertSame($this->id, $note->getId());
        $this->assertSame($this->userId, $note->getAuthorId());
        $this->assertSame($this->uuid, $note->getObjectId());
        $this->assertSame($this->content, $note->getContent());
        $this->assertEmpty($note->getEditedAt());
        $this->assertNotEmpty($note->getCreatedAt());
    }

    /**
     * @throws \Exception
     */
    public function testContendEditCreation(): void
    {
        $newContent = 'new Content';
        $note = new Note($this->id, $this->uuid, $this->userId, $this->content);
        $note->changeContent($newContent);
        $this->assertSame($newContent, $note->getContent());
        $this->assertNotEmpty($note->getEditedAt());
    }
}
