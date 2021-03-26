<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Handler;

use Ergonode\Category\Domain\Command\UpdateCategoryCommand;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Category\Application\Event\CategoryUpdatedEvent;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class UpdateCategoryCommandHandler
{
    private CategoryRepositoryInterface $repository;

    private ApplicationEventBusInterface $eventBus;

    public function __construct(CategoryRepositoryInterface $repository, ApplicationEventBusInterface $eventBus)
    {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateCategoryCommand $command): void
    {
        $category = $this->repository->load($command->getId());
        Assert::notNull($category);
        $category->changeName($command->getName());
        $this->repository->save($category);
        $this->eventBus->dispatch(new CategoryUpdatedEvent($category));
    }
}
