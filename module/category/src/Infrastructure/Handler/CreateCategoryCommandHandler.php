<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Handler;

use Ergonode\Category\Domain\Command\CreateCategoryCommand;
use Ergonode\Category\Domain\Factory\CategoryFactory;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Application\Event\CategoryCreateEvent;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class CreateCategoryCommandHandler
{
    private CategoryFactory $factory;

    private CategoryRepositoryInterface $repository;

    private ApplicationEventBusInterface $eventBus;

    public function __construct(
        CategoryFactory $factory,
        CategoryRepositoryInterface $repository,
        ApplicationEventBusInterface $eventBus
    ) {
        $this->factory = $factory;
        $this->repository = $repository;
        $this->eventBus = $eventBus;
    }

    public function __invoke(CreateCategoryCommand $command): void
    {
        $category = $this->factory->create(
            $command->getId(),
            $command->getCode(),
            $command->getName()
        );

        $this->repository->save($category);
        $this->eventBus->dispatch(new CategoryCreateEvent($category));
    }
}
