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

class UpdateCategoryCommandHandler
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
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
    }
}
