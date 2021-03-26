<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Infrastructure\Handler;

use Ergonode\Category\Domain\Command\UpdateCategoryCommand;
use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Infrastructure\Handler\UpdateCategoryCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class UpdateCategoryCommandHandlerTest extends TestCase
{
    private CategoryRepositoryInterface $repository;

    private UpdateCategoryCommand $command;

    private ApplicationEventBusInterface $eventBus;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CategoryRepositoryInterface::class);
        $this->command = $this->createMock(UpdateCategoryCommand::class);
        $this->eventBus = $this->createMock(ApplicationEventBusInterface::class);
    }

    public function testHandlingExistsCategory(): void
    {
        $category = $this->createMock(AbstractCategory::class);
        $category->expects($this->once())->method('changeName');
        $this->repository->expects($this->once())->method('load')->willReturn($category);
        $this->repository->expects($this->once())->method('save');

        $handler = new UpdateCategoryCommandHandler($this->repository, $this->eventBus);
        $handler->__invoke($this->command);
    }

    public function testHandlingNotExistsCategory(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->repository->expects($this->once())->method('load')->willReturn(null);
        $this->repository->expects($this->never())->method('save');
        $handler = new UpdateCategoryCommandHandler($this->repository, $this->eventBus);
        $handler->__invoke($this->command);
    }
}
