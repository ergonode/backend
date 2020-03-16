<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Infrastructure\Handler;

use Ergonode\Comment\Domain\Command\UpdateCommentCommand;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\Comment\Domain\Repository\CommentRepositoryInterface;
use Ergonode\Comment\Infrastructure\Handler\UpdateCommentCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateCommentCommandHandlerTest extends TestCase
{
    /**
     * @var CommentRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var UpdateCommentCommand|MockObject
     */
    private $command;

    /**
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(CommentRepositoryInterface::class);

        $this->command = $this->createMock(UpdateCommentCommand::class);
    }

    /**
     */
    public function testHandlingExistsObject(): void
    {
        $this->repository->expects($this->once())->method('load')->willReturn($this->createMock(Comment::class));
        $this->repository->expects($this->once())->method('save');
        $handler = new UpdateCommentCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }

    /**
     */
    public function testHandlingCommentExistsObject(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->expects($this->once())->method('load');
        $handler = new UpdateCommentCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
