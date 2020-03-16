<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Infrastructure\Handler;

use Ergonode\Comment\Domain\Command\DeleteCommentCommand;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\Comment\Domain\Repository\CommentRepositoryInterface;
use Ergonode\Comment\Infrastructure\Handler\DeleteCommentCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteCommentCommandHandlerTest extends TestCase
{
    /**
     * @var CommentRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var DeleteCommentCommand|MockObject
     */
    private $command;

    /**
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(CommentRepositoryInterface::class);

        $this->command = $this->createMock(DeleteCommentCommand::class);
    }

    /**
     */
    public function testHandlingExistsObject(): void
    {
        $this->repository->expects($this->once())->method('load')->willReturn($this->createMock(Comment::class));
        $this->repository->expects($this->once())->method('delete');
        $handler = new DeleteCommentCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }

    /**
     */
    public function testHandlingCommentxistsObject(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->expects($this->once())->method('load');
        $handler = new DeleteCommentCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
