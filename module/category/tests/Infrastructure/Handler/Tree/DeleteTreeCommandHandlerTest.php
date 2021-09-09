<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Infrastructure\Handler\Tree;

use Ergonode\Category\Domain\Command\Tree\DeleteTreeCommand;
use Ergonode\Category\Domain\Entity\CategoryTree;
use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Category\Infrastructure\Handler\Tree\DeleteTreeCommandHandler;
use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Model\Relationship;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteTreeCommandHandlerTest extends TestCase
{
    /**
     * @var TreeRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var DeleteTreeCommand|MockObject
     */
    private $command;

    private RelationshipsResolverInterface $resolver;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(TreeRepositoryInterface::class);
        $this->command = $this->createMock(DeleteTreeCommand::class);
        $this->resolver = $this->createMock(RelationshipsResolverInterface::class);
    }

    public function testHandlingExistsTreeWithoutRelations(): void
    {
        $tree = $this->createMock(CategoryTree::class);
        $this->repository->expects($this->once())->method('load')->willReturn($tree);
        $this->repository->expects($this->once())->method('delete');

        $handler = new DeleteTreeCommandHandler($this->repository, $this->resolver);
        $handler->__invoke($this->command);
    }

    public function testHandlingExistsTreeWithRelations(): void
    {
        $this->expectException(ExistingRelationshipsException::class);
        $collection = $this->createMock(Relationship::class);
        $this->resolver->expects($this->once())->method('resolve')->willReturn($collection);
        $tree = $this->createMock(CategoryTree::class);
        $this->repository->expects($this->once())->method('load')->willReturn($tree);
        $this->repository->expects($this->never())->method('delete');

        $handler = new DeleteTreeCommandHandler($this->repository, $this->resolver);
        $handler->__invoke($this->command);
    }

    public function testHandlingNotExistsTree(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->expects($this->once())->method('load')->willReturn(null);
        $this->repository->expects($this->never())->method('save');

        $handler = new DeleteTreeCommandHandler($this->repository, $this->resolver);
        $handler->__invoke($this->command);
    }
}
