<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Infrastructure\Handler;

use Ergonode\Category\Domain\Command\DeleteCategoryCommand;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Infrastructure\Handler\DeleteCategoryCommandHandler;
use Ergonode\Core\Infrastructure\Model\RelationshipCollection;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteCategoryCommandHandlerTest extends TestCase
{
    /**
     * @var CategoryRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var DeleteCategoryCommand|MockObject
     */
    private $command;

    /**
     * @var RelationshipsResolverInterface
     */
    private $resolver;

    /**
     */
    protected function setUp()
    {
        $this->repository = $this->createMock(CategoryRepositoryInterface::class);
        $this->command = $this->createMock(DeleteCategoryCommand::class);
        $this->resolver = $this->createMock(RelationshipsResolverInterface::class);
    }

    /**
     */
    public function testHandlingExistsCategoryWithoutRelations(): void
    {
        $collection = $this->createMock(RelationshipCollection::class);
        $collection->method('isEmpty')->willReturn(true);
        $this->resolver->expects($this->once())->method('resolve')->willReturn($collection);
        $category = $this->createMock(Category::class);
        $this->repository->expects($this->once())->method('load')->willReturn($category);
        $this->repository->expects($this->once())->method('delete');

        $handler = new DeleteCategoryCommandHandler($this->repository, $this->resolver);
        $handler->__invoke($this->command);
    }

    /**
     * @expectedException \Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException
     */
    public function testHandlingExistsCategoryWithRelations(): void
    {
        $collection = $this->createMock(RelationshipCollection::class);
        $collection->method('isEmpty')->willReturn(false);
        $this->resolver->expects($this->once())->method('resolve')->willReturn($collection);
        $category = $this->createMock(Category::class);
        $this->repository->expects($this->once())->method('load')->willReturn($category);
        $this->repository->expects($this->never())->method('delete');

        $handler = new DeleteCategoryCommandHandler($this->repository, $this->resolver);
        $handler->__invoke($this->command);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testHandlingNotExistsCategory(): void
    {
        $this->repository->expects($this->once())->method('load')->willReturn(null);
        $this->repository->expects($this->never())->method('save');
        $handler = new DeleteCategoryCommandHandler($this->repository, $this->resolver);
        $handler->__invoke($this->command);
    }
}
