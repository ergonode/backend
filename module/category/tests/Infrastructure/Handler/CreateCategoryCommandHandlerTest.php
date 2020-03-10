<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Infrastructure\Handler;

use Ergonode\Category\Domain\Command\CreateCategoryCommand;
use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Domain\Factory\CategoryFactory;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Infrastructure\Handler\CreateCategoryCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateCategoryCommandHandlerTest extends TestCase
{
    /**
     * @var CategoryFactory|MockObject
     */
    private $factory;

    /**
     * @var CategoryRepositoryInterface|MockObject
     */
    private $repository;

    /**
     * @var CreateCategoryCommand|MockObject
     */
    private $command;

    /**
     */
    protected function setUp(): void
    {
        $this->factory = $this->createMock(CategoryFactory::class);
        $this->factory->expects($this->once())->method('create')->willReturn($this->createMock(Category::class));
        $this->repository = $this->createMock(CategoryRepositoryInterface::class);
        $this->repository->expects($this->once())->method('save');
        $this->command = $this->createMock(CreateCategoryCommand::class);
    }

    /**
     */
    public function testHandling(): void
    {
        $handler = new CreateCategoryCommandHandler($this->factory, $this->repository);
        $handler->__invoke($this->command);
    }
}
