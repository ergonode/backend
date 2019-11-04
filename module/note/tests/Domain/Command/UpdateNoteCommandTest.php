<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Tests\Domain\Command;

use Ergonode\Note\Domain\Command\UpdateNoteCommand;
use Ergonode\Note\Domain\Entity\NoteId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class UpdateNoteCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        /** @var NoteId $noteId */
        $noteId = $this->createMock(NoteId::class);
        /** @var Uuid $objectId */
        $content = 'Any content';

        $command = new UpdateNoteCommand($noteId, $content);
        $this->assertSame($noteId, $command->getId());
        $this->assertSame($content, $command->getContent());
    }
}
