<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Handler;

use Ergonode\Category\Domain\Command\DeleteCategoryCommand;
use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Webmozart\Assert\Assert;
use Ergonode\Category\Application\Event\CategoryDeletedEvent;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;

class DeleteCategoryCommandHandler
{
    private CategoryRepositoryInterface $repository;

    private RelationshipsResolverInterface $relationshipsResolver;

    private ApplicationEventBusInterface $eventBus;

    public function __construct(
        CategoryRepositoryInterface $repository,
        RelationshipsResolverInterface $relationshipsResolver,
        ApplicationEventBusInterface $eventBus
    ) {
        $this->repository = $repository;
        $this->relationshipsResolver = $relationshipsResolver;
        $this->eventBus = $eventBus;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteCategoryCommand $command): void
    {
        $category = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $category,
            AbstractCategory::class,
            sprintf('Can\'t find category with id "%s"', $command->getId())
        );

        $relationships = $this->relationshipsResolver->resolve($command->getId());
        if (null !== $relationships) {
            throw new ExistingRelationshipsException($command->getId());
        }

        $this->repository->delete($category);
        $this->eventBus->dispatch(new CategoryDeletedEvent($category));
    }
}
