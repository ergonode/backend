<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Tests\Infrastructure\Handler;

use Ergonode\Category\Domain\Command\CreateCategoryCommand;
use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Category\Domain\Factory\CategoryFactory;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Infrastructure\Handler\CreateCategoryCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class CreateCategoryCommandHandlerTest extends TestCase
{
    private CategoryFactory $factory;

    private CategoryRepositoryInterface $repository;

    private CreateCategoryCommand $command;

    private ApplicationEventBusInterface $eventBus;

    protected function setUp(): void
    {
        $this->factory = $this->createMock(CategoryFactory::class);
        $this->factory->expects($this->once())->method('create')
            ->willReturn($this->createMock(AbstractCategory::class));
        $this->repository = $this->createMock(CategoryRepositoryInterface::class);
        $this->repository->expects($this->once())->method('save');
        $this->command = $this->createMock(CreateCategoryCommand::class);
        $this->eventBus = $this->createMock(ApplicationEventBusInterface::class);
    }

    public function testHandling(): void
    {
        $handler = new CreateCategoryCommandHandler($this->factory, $this->repository, $this->eventBus);
        $handler->__invoke($this->command);
    }
}
