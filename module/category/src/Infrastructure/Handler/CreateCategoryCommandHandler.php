<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Handler;

use Ergonode\Category\Domain\Command\CreateCategoryCommand;
use Ergonode\Category\Domain\Factory\CategoryFactory;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;

/**
 */
class CreateCategoryCommandHandler
{
    /**
     * @var CategoryFactory
     */
    private CategoryFactory $factory;

    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $repository;

    /**
     * @param CategoryFactory             $factory
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryFactory $factory, CategoryRepositoryInterface $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @param CreateCategoryCommand $command
     */
    public function __invoke(CreateCategoryCommand $command): void
    {
        $category = $this->factory->create(
            $command->getId(),
            $command->getCode(),
            $command->getName()
        );

        $this->repository->save($category);
    }
}
