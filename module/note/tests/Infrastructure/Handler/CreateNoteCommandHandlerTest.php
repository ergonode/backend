<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Tests\Infrastructure\Handler;

use Ergonode\Note\Domain\Command\CreateNoteCommand;
use Ergonode\Note\Domain\Entity\Note;
use Ergonode\Note\Domain\Factory\NoteFactory;
use Ergonode\Note\Domain\Repository\NoteRepositoryInterface;
use Ergonode\Note\Infrastructure\Handler\CreateNoteCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateNoteCommandHandlerTest extends TestCase
{
    /**
     * @var NoteFactory|MockObject
     */
    private $factory;

    /**
     * @var NoteRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var CreateNoteCommand|MockObject
     */
    private $command;

    /**
     */
    protected function setUp()
    {
        $this->factory = $this->createMock(NoteFactory::class);
        $this->factory->expects($this->once())->method('create')->willReturn($this->createMock(Note::class));
        $this->repository = $this->createMock(NoteRepositoryInterface::class);
        $this->repository->expects($this->once())->method('save');
        $this->command = $this->createMock(CreateNoteCommand::class);
    }

    /**
     */
    public function testHandling(): void
    {
        $handler = new CreateNoteCommandHandler($this->repository, $this->factory);
        $handler->__invoke($this->command);
    }
}
