<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Tests\Infrastructure\Handler;

use Ergonode\Note\Domain\Command\UpdateNoteCommand;
use Ergonode\Note\Domain\Entity\Note;
use Ergonode\Note\Domain\Repository\NoteRepositoryInterface;
use Ergonode\Note\Infrastructure\Handler\UpdateNoteCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateNoteCommandHandlerTest extends TestCase
{
    /**
     * @var NoteRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var UpdateNoteCommand|MockObject
     */
    private $command;

    /**
     */
    protected function setUp()
    {
        $this->repository = $this->createMock(NoteRepositoryInterface::class);

        $this->command = $this->createMock(UpdateNoteCommand::class);
    }

    /**
     */
    public function testHandlingExistsObject(): void
    {
        $this->repository->expects($this->once())->method('load')->willReturn($this->createMock(Note::class));
        $this->repository->expects($this->once())->method('save');
        $handler = new UpdateNoteCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testHandlingNotExistsObject(): void
    {
        $this->repository->expects($this->once())->method('load');
        $handler = new UpdateNoteCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
