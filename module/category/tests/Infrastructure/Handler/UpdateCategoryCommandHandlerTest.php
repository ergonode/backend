<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Infrastructure\Handler;

use Ergonode\Category\Domain\Command\UpdateCategoryCommand;
use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Infrastructure\Handler\UpdateCategoryCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateCategoryCommandHandlerTest extends TestCase
{
    /**
     * @var CategoryRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var UpdateCategoryCommand|MockObject
     */
    private $command;

    /**
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(CategoryRepositoryInterface::class);
        $this->command = $this->createMock(UpdateCategoryCommand::class);
    }

    /**
     */
    public function testHandlingExistsCategory(): void
    {
        $category = $this->createMock(AbstractCategory::class);
        $category->expects($this->once())->method('changeName');
        $this->repository->expects($this->once())->method('load')->willReturn($category);
        $this->repository->expects($this->once())->method('save');

        $handler = new UpdateCategoryCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }

    /**
     */
    public function testHandlingNotExistsCategory(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->expects($this->once())->method('load')->willReturn(null);
        $this->repository->expects($this->never())->method('save');
        $handler = new UpdateCategoryCommandHandler($this->repository);
        $handler->__invoke($this->command);
    }
}
