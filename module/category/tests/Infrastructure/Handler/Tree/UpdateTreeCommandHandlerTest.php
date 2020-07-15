<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Infrastructure\Handler\Tree;

use Ergonode\Category\Domain\Command\Tree\UpdateTreeCommand;
use Ergonode\Category\Domain\Entity\CategoryTree;
use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Category\Infrastructure\Handler\Tree\UpdateTreeCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateTreeCommandHandlerTest extends TestCase
{
    /**
     * @var TreeRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var UpdateTreeCommand|MockObject
     */
    private $command;

    /**
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(TreeRepositoryInterface::class);
        $this->command = $this->createMock(UpdateTreeCommand::class);
    }

    /**
     */
    public function testHandlingExistsTree(): void
    {
        $tree = $this->createMock(CategoryTree::class);
        $tree->expects($this->once())->method('changeName');
        $this->repository->expects($this->once())->method('load')->willReturn($tree);
        $this->repository->expects($this->once())->method('save');

        $handler = new UpdateTreeCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }

    /**
     */
    public function testHandlingNotExistsTree(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->expects($this->once())->method('load')->willReturn(null);
        $this->repository->expects($this->never())->method('save');
        $handler = new UpdateTreeCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
