<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Tests\Domain\Command;

use Ergonode\Note\Domain\Command\DeleteNoteCommand;
use Ergonode\Note\Domain\Entity\NoteId;
use PHPUnit\Framework\TestCase;

class DeleteNoteCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        /** @var NoteId $noteId */
        $noteId = $this->createMock(NoteId::class);

        $command = new DeleteNoteCommand($noteId);
        $this->assertSame($noteId, $command->getId());
    }
}
