<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Infrastructure\Handler;

use Ergonode\Comment\Domain\Command\CreateCommentCommand;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\Comment\Domain\Factory\CommentFactory;
use Ergonode\Comment\Domain\Repository\CommentRepositoryInterface;
use Ergonode\Comment\Infrastructure\Handler\CreateCommentCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateCommentCommandHandlerTest extends TestCase
{
    /**
     * @var CommentFactory|MockObject
     */
    private $factory;

    /**
     * @var CommentRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var CreateCommentCommand|MockObject
     */
    private $command;

    /**
     */
    protected function setUp(): void
    {
        $this->factory = $this->createMock(CommentFactory::class);
        $this->factory->expects($this->once())->method('create')->willReturn($this->createMock(Comment::class));
        $this->repository = $this->createMock(CommentRepositoryInterface::class);
        $this->repository->expects($this->once())->method('save');
        $this->command = $this->createMock(CreateCommentCommand::class);
    }

    /**
     */
    public function testHandling(): void
    {
        $handler = new CreateCommentCommandHandler($this->repository, $this->factory);
        $handler->__invoke($this->command);
    }
}
